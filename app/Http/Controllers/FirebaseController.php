<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception;
use Kreait\Firebase\Database;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;
use Validator;

class FirebaseController extends Controller
{
    public function index() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $newPost = $database
            ->getReference('blog/posts')
            ->push([
                'title' => 'Post title',
                'body' => 'This should probably be longer.']);
        /*
        $newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
        $newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-
        $newPost->getChild('title')->set('Changed post title');
        $newPost->getValue(); // Fetches the data from the realtime database
        $newPost->remove();*/
        
        echo"<pre>";
        print_r($newPost->getvalue());
    }

    public function login(Request $request) {
        $email = $request->input('inputEmail');
        $password = $request->input('inputPassword');
        $rules = array(
            'inputEmail' => 'required',
            'inputPassword' => 'required'
        );
        $validator = Validator::make($request->all(),$rules);
        try {
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
            $auth = $firebase->getAuth();
            $user = $auth->verifyPassword($email, $password);

            $request->session()->put('userId', $user->uid);
            return redirect('/');
        } catch (\Exception $e) {
            $validator->getMessageBag()->add('loginError', $e->getMessage());
            return redirect('login/')->withErrors($validator);
        }
    }

    public function register(Request $request){
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('inputEmail');
        $password = $request->input('inputPassword');
        $phoneNo = $request->input('inputPhone');

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'inputPhone' => 'required',
            'inputEmail' => 'required|email|min:6',
            'inputPassword' => 'required|min:6',
            'inputPassword2' => 'required|same:inputPassword|min:6',
        ]);

        if ($validator->fails()) {
            return redirect('register/')->withErrors($validator)->withInput();
        }

        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            'password' => $password,
        ];

        $userProfile = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' =>  $email,
            'phoneNo' => $phoneNo
        ];

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $auth = $firebase->getAuth();

        try {
            $createdUser = $auth->createUser($userProperties);
            $createdUid = $createdUser->uid;

            $database = $firebase->getDatabase();
            $newUser = $database->getReference()
                ->getChild('Users')
                ->getChild($createdUid)
                ->set($userProfile);

            return redirect('login/')->with('status', 'You registered an account. Now you can login!');
        } catch (\Exception $e) {
            $validator->getMessageBag()->add('registerError', $e->getMessage());
            return redirect('register/')->withErrors($validator);
        }
    }

    public function addProperty(Request $request){
        if(!session()->has('userId'))
            return "[Error]: Cannot update property - user not logged in.";

        $description = $request->input('inputDescription');
        $bathrooms = $request->input('inputBathrooms');
        $bedrooms = $request->input('inputBedrooms');
        $location = $request->input('inputLocation');
        $price = $request->input('inputPrice');
        $area = $request->input('inputArea');
        $toRent = $request->input('inputToRent') == NULL ? 1 : 0;
        $type = $toRent . $bathrooms . $bedrooms . $location;
        $dateAdded = date('Y-m-d');

        $validator = Validator::make($request->all(), [
            'inputDescription' => 'required|min:3|max:25',
            'inputBathrooms' => 'required|numeric|min:1',
            'inputBedrooms' => 'required|numeric|min:1',
            'inputLocation' => 'required|integer',
            'inputPrice' => 'required|numeric|min:1',
            'inputArea' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect('add-property/')->withErrors($validator)->withInput();
        }

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();

        $newProperty = $database
            ->getReference('Properties')
            ->push([
                'area' => $area,
                'bathrooms' => $bathrooms,
                'bedrooms' => $bedrooms,
                'dateAdded' => $dateAdded,
                'description' => $description,
                'location' => $location,Â
                'price' => $price,
                'toRent' => $toRent,
                'type' => $type,
                'owner' => session()->get('userId'),
                'lat' => 51.512053,
                'lng' => -0.085472
            ]);
        return redirect('properties/')->with('status', 'You added the property succesfully!');
    }

    public function updateProperty(Request $request) {
        if(!session()->has('userId'))
            return "[Error]: Cannot update property - user not logged in.";

        $description = $request->input('description');
        $bathrooms = $request->input('bathrooms');
        $bedrooms = $request->input('bedrooms');
        $location = $request->input('location');
        $price = ltrim($request->input('price'),"£");
        $area = substr($request->input('area'), 0, -4);
        $dateAdded = $request->input('addedOn');
        $propertyId = $request->input('propertyId');
        $owner = $request->input('owner');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $toRent = $request->input('availability') == "To Rent" ? 1 : 0;

        if(session()->get('userId') != $owner)
            return "[Error]: Cannot update property - invalid permissions.";

        $updates = [
            'description' => $description,
            'bathrooms' => $bathrooms,
            'bedrooms' => $bedrooms,
            'location' => $location,
            'price' => $price,
            'area' => $area,
            'dateAdded' => $dateAdded,
            'toRent' => $toRent,
            'lat' => $lat,
            'lng' => $lng
        ];

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $database   ->getReference('Properties/'.$propertyId)
                    ->update($updates);
    }

    public function searchProperty(Request $request) {
        $rent = $request->input('toRent');
        $bathrooms = $request->input('bathrooms');
        $bedrooms = $request->input('bedrooms');
        $location = $request->input('location');
        $toRent = isset($rent) ? 1 : 0;

        $validator = Validator::make($request->all(), [
            'bathrooms' => 'required|integer',
            'bedrooms' => 'required|integer',
            'location' => 'required|integer',
        ],
        [
            'bathrooms.integer' => 'You didn\'t choose the number of bathrooms',
            'bedrooms.integer' => 'You didn\'t choose the number of bedrooms',
            'location.integer' => 'You didn\'t choose the location'
        ]);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator)->withInput();
        }

        $type = $toRent . $bathrooms . $bedrooms . $location;

        return redirect()->route('searchProperties', [$type]);
    }

    public function deleteProperty($id) {
        if(!session()->has('userId'))
            return "[Error]: Cannot update property - user not logged in.";

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $database   ->getReference('Properties/'.$id)
                    ->remove();
    }

    public function uploadObject($bucketName, $objectName, $source) {
        $projectId = 'cloudestate-d10da';
        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFilePath' => __DIR__.'/StorageAcc.json'
        ]);

        $file = fopen($source, 'r');

        $bucket = $storage->bucket("images");
        $object = $bucket->upload($file, [
            'name' => $objectName
        ]);
        printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($source), $bucketName, $objectName);

    }

    public function imageUpload(){
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];

        $TMPSS = explode('.',$file_name);
        $file_ext = strtolower(end($TMPSS));

        $expensions = array("jpeg","jpg","png");
        if(in_array($file_ext,$expensions)=== false)
            return redirect('properties/')->with('errors', 'Extension not allowed, please choose a JPEG or PNG file.');

        if($file_size > 2097152)
            return redirect('properties/')->with('errors', 'File size must be excately 2 MB');

        move_uploaded_file($file_tmp,"images/".$file_name);

        $this->uploadObject("images", $file_name, "images/".$file_name);
    }
}
?>