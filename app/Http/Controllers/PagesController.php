<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;

use FirebaseAuth;

class PagesController extends Controller
{
    // Class Methods:

    public function retrieveProperties() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Properties')
            ->getValue();
    }

    public function retrieveLast3Properties() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Properties')
            ->orderByChild('dateAdded')
            ->limitToFirst(3)
            ->getValue();
    }

    public function retrieveLocations() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Locations')
            ->getValue();
    }

    public function retrievePropertiesByType($type) {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Properties')
            ->orderByChild('type')
            ->equalTo($type)
            ->getValue();
    }

    public function getPropertyById($id) {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Properties/'.$id)
            ->getValue();
    }

    public function getOwnerDetails($userId) {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://cloudestate-d10da.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        return $database
            ->getReference('Users/'.$userId)
            ->getValue();
    }

    // Static Pages:

    public function index() {
        $data = array(
            'title' => 'Homepage',
            'properties' => $this->retrieveLast3Properties(),
            'locations' => $this->retrieveLocations()
        );
        return view('pages.index')->with($data);
    }

    public function contact() {

        $data = array(
            'title' => 'Contact'
        );
        return view('pages.contact')->with($data);
    }

    // Property Pages:

    public function addProperty() {
        if(!session()->has('userId'))
            return redirect()->route('index');

        $data = array(
            'title' => 'Add a new property',
            'locations' => $this->retrieveLocations()
        );
        return view('pages.addproperty')->with($data);
    }

    public function viewProperty($id) {
        $property = $this->getPropertyById($id);
        if($property == NULL)
            return redirect('properties/')->with('error', 'Invalid location id.');

        $owner = $this->getOwnerDetails($property['owner']);
        $data = array(
            'title' => 'Property #'.$id,
            'propertyId' => $id,
            'property' => $property,
            'owner' => $owner
        );
        return view('pages.viewproperty')->with($data);
    }

    public function searchproperty($type) {
        $properties = $this->retrievePropertiesByType($type);
        if(count($properties) == 0)
            return redirect('/')->with('error', 'There are no properties that matches your search');

        $data = array(
            'title' => 'Search Result',
            'properties' => $properties
        );
        return view('pages.searchproperties')->with($data);
    }

    public function properties() {
        $data = array(
            'title' => 'Properties',
            'properties' => $this->retrieveProperties()
        );
        return view('pages.properties')->with($data);
    }

    // User Account Pages:

    public function login() {
        $data = array(
            'title' => 'Login'
        );
        return view('pages.login')->with($data);
    }

    public function register() {
        $data = array(
            'title' => 'Register'
        );
        return view('pages.register')->with($data);
    }

    public function logout() {
        Session::flush();
        return redirect()->action('PagesController@index');
    }
}
