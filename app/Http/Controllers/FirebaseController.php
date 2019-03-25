<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Firebase\User;
use Kreait\Firebase\Exception;
use Kreait\Firebase\Database;
use App\Firebase\FirebaseAuth;
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

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
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
        $description = $request->input('inputDescription');
        $bathrooms = $request->input('inputBathrooms');
        $bedrooms = $request->input('inputBedrooms');
        $location = $request->input('inputLocation');
        $price = $request->input('inputPrice');
        $area = $request->input('inputArea');
        $type = $request->input('inputType');
        $toRent = $request->input('inputToRent') == NULL ? 1 : 0;
        $dateAdded = date('Y-m-d');

        $validator = Validator::make($request->all(), [
            'inputDescription' => 'required|min:3|max:25',
            'inputBathrooms' => 'required|numeric|min:1',
            'inputBedrooms' => 'required|numeric|min:1',
            'inputLocation' => 'required|min:3',
            'inputPrice' => 'required|numeric|min:1',
            'inputArea' => 'required|numeric|min:1',
            'inputType' => 'required|numeric|min:1',
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
                'location' => $location,
                'price' => $price,
                'toRent' => $toRent,
                'type' => $type]);
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
        $toRent = $request->input('availability') == "To Rent" ? 1 : 0;

        $updates = [
            'description' => $description,
            'bathrooms' => $bathrooms,
            'bedrooms' => $bedrooms,
            'location' => $location,
            'price' => $price,
            'area' => $area,
            'dateAdded' => $dateAdded,
            'toRent' => $toRent
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
}
?>