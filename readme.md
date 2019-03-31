## About CloudEstate

Cloud Estate is a web-based cloud application (SaaS) that allows users to advertise, rent or buy properties in a certain area. This application will make use of the Google Cloud service to store all the data regarding the houses or flats as well as pictures and user details. To advertise properties, users are required to create an account. They will then be able to fill in the required details about the listing, such as location, number of rooms, pictures or the price. Users who are not listing on the website will be able to view the available properties without creating an account and will have the possibility to search them based on keywords or postcode as well as more refined searching criteria, like price range or distance from the searched location.

## Implementation

The main part of the application will be developed using php which does the logic behind the website and links it to the cloud service. The cloud service used is provided by Google Cloud Platform out of which we specifically used the Firebase service to set up the login system (users will be able login using Google accounts or sign up using any other email address) and handle the database. The API requests are managed using the Google Cloud console and Google Cloud Storage will be used for storing the pictures for the properties.
The front end of the application will be developed in HTML, CSS and JavaScript added on top of an HTML template.

## Authors

- Gabriel Baeasu: Framework Installation, Login and Register
- Mihai Matraguna: Inserting and Retrieving data from cloud, Cloud project setup, Updating data to the cloud, Search method
- Patryk Gulbinovic – Google Maps functionalities, Validating, Testing, Security Testing.


## Resources used:

- Laravel
- Holmes HTML Boostrap template
- Firebase Database (file included in the root directory)
