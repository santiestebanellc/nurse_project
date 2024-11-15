# Medical data management project

## Description

The purpose of this project is to create an app for the students of nursing vocational training, so they can use it while practising in class. 

The app has to fulfil all the next functionalities:

Nurses Management:
- LogIn
- SignIn
- Modify
- Delete

Patients Management:
- Create a new patient profile
- Modify patient profile data
- Incorporation of Patient Data


## Key Features

- **Authentication and Authorization**: Implementation of a login system with role-based access control.
- **Entity Management**: Utilizes Doctrine ORM for data modeling and database interactions.
- **Unit Testing**: PHPUnit setup for ensuring code quality.
- **Modular Architecture**: Component-based structure for scalability and maintainability.

### Prerequisites

Before setting up the project, ensure you have the following installed:

- PHP >= 8.1
- Composer
- Symfony CLI (optional but recommended)
- Web server (Apache or Nginx)
- Database (MySQL or PostgreSQL)

### Installing

Follow these steps to set up the project locally:

1. Clone the repository:
   ```bash
   git clone https://github.com/santiestebanellc/nurse_project.git

2. Navigate to the project directory:
   ```bash
   cd nurse_project

3. Install dependencies:
   ```bash
   composer install

4. Comment the current ``` DATABASE_URL=mysql ``` on the .env file and update the necessary values:
   ```bash
   DATABASE_URL=mysql://username:password@127.0.0.1:3306/database_name

5. Run migrations to set up the database schema:
   ```bash
   php bin/console doctrine:migrations:migrate

6. Start the development server:
   ```bash
   symfony serve
   ```

   or
   
    ```bash
   symfony server:start
    

## Running the tests

You can run test in two ways:

- Via terminal:
You have to write in the terminal ```./name_of_the_tests_directory ```


- Via VSCode interface:

First you have to go to the test section.

<img width="59" alt="Captura de pantalla 2024-11-15 a las 18 46 17" src="https://github.com/user-attachments/assets/d64dc246-93ed-426c-8dab-8a4afdfff201">

Then you can choose to run all the tests at once, or to run each test individually.

<img width="287" alt="Captura de pantalla 2024-11-15 a las 18 47 30" src="https://github.com/user-attachments/assets/80badcf5-5d35-453b-a86f-ee571b746369">


## Authors

  - **Akisha Angeles** -
    [akishajae](https://github.com/akishajae)

  - **Arnau Colominas** -
    [ColoSans1](https://github.com/ColoSans1)

  - **Mònica Fernàndez** -
    [Jinninni040507](https://github.com/Jinninni040507)

  - **Santi Estebanell** - *Project Manager* -
    [santiestebanellc](https://github.com/santiestebanellc)
