
# Custom Jobs Runner

Custom system to execute PHP classes as background jobs, independent of Laravel's built-in queue system.


## Author

- Facundo Condal


## Run Locally

Clone the project.

```bash
  git clone https://github.com/fcondal/custom-jobs-runner.git
```

Go to the project directory.

```bash
  cd custom-jobs-runner
```

Create `.env` based on `.env.example`

```bash
  cp .env.example .env
```

Configure MySQL database credentials

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=test
DB_PASSWORD=test
```

Create and start containers.

```bash
  docker compose up -d
```

Install dependencies.

```bash
  docker exec -ti php-fpm composer install
```

Run migrations.

```bash
  docker exec -ti php-fpm php artisan migrate
```

Execute Administrator Seeder to create admin user.

```bash
  docker exec -ti php-fpm php artisan db:seed AdministratorSeeder
```

Assign `www-data` group to files and folders.

```bash
docker exec -ti php-fpm chown -R 1000:www-data /application
```

Set permissions to storage and cache folders.

```bash
docker exec -ti php-fpm chmod -R 775 /application/storage /application/bootstrap/cache
```

Generate application key

```bash
docker exec -ti php-fpm php artisan key:generate
```

Execute the following command to run scheduler.

```bash
  docker exec -ti php-fpm php artisan schedule:work
```

Login with the following credentials on http://localhost:30000 to get access to custom job list detail.

```bash
  Email: admin@custom-job.com
  Password: admin
```


## Environment Variables

`CUSTOM_JOBS_RETRIES` environment variable is used to set the retries quantity per execution job. If this variable is not set, it will take 1 as default value.

```
CUSTOM_JOBS_RETRIES

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=test
DB_PASSWORD=test
```


## Documentation

The global helper function `runBackgroundJob` is available to execute in background class.

Parameters of the method:

- **class**: required
- **method**: required
- **parameters**: optional - Default value: []
- **delay**: optional - Default value: 0 - Time is defined in seconds.
- **priority**: optional - Possible values: low, default, high - Default value: default

Classes that are located in `App\CustomJobs` are allowed to be executed with `runBackgroundJob`.

There is a table called `custom_jobs` where are stored all the custom jobs information with a status associated (pending, running, completed, failed).
Every status is logged on background_jobs.log located in `storage/logs`.
Failed status is also logged on background_jobs_errors.log and is localted in the same folder.

Custom Job retries can be configurated on `.env`.

Delay parameter defines the amount of **SECONDS** that a Job will be delayed.

The simplest way to test is using Tinker, but you can use it on a Controller or any other location.

If you'd like to test priority, the best way to do it is:
- Stop scheduler
- Create some custom jobs with different priorities
- Start scheduler checking on custom jobs table or background_jobs log.

## Usage/Examples

Execute on Tinker `docker exec -ti php-fpm php artisan tinker`

### Success

**Example 1**

```php
runBackgroundJob('App\CustomJobs\None', 'executeNone')
```

Output:

```
[2024-11-14 05:21:34] local.INFO: Custom job: App\CustomJobs\None::executeNone - Status: pending  
[2024-11-14 05:21:35] local.INFO: Custom Job: App\CustomJobs\None::executeNone - Status: processing  
[2024-11-14 05:21:35] local.INFO: This message is called from App\CustomJobs\None::executeNone  
[2024-11-14 05:21:35] local.INFO: Custom Job: App\CustomJobs\None::executeNone - Status: completed 
```

**Example 2**

```php
runBackgroundJob('App\CustomJobs\One', 'executeOne', ['param1'])
```

Output:

```
[2024-11-14 05:22:52] local.INFO: Custom job: App\CustomJobs\One::executeOne - Status: pending  
[2024-11-14 05:22:56] local.INFO: Custom Job: App\CustomJobs\One::executeOne - Status: processing  
[2024-11-14 05:22:56] local.INFO: This message is called from App\CustomJobs\One::executeOne  
[2024-11-14 05:22:56] local.INFO: Custom Job: App\CustomJobs\One::executeOne - Status: completed  
```

**Example 3**

```php
runBackgroundJob('App\CustomJobs\Two', 'executeTwo', ['param1', 'param2'])
```

Output:

```
[2024-11-14 05:24:00] local.INFO: Custom job: App\CustomJobs\Two::executeTwo - Status: pending  
[2024-11-14 05:24:05] local.INFO: Custom Job: App\CustomJobs\Two::executeTwo - Status: processing  
[2024-11-14 05:24:05] local.INFO: This message is called from App\CustomJobs\Two::executeTwo  
[2024-11-14 05:24:05] local.INFO: Custom Job: App\CustomJobs\Two::executeTwo - Status: completed  
```

**Example 4**

```php
runBackgroundJob('App\CustomJobs\Three', 'executeThree', ['param1', 'param2', 'param3'], 3, 'high')
```

Output:

```
[2024-11-14 05:26:11] local.INFO: Custom job: App\CustomJobs\Three::executeThree - Status: pending  
[2024-11-14 05:26:15] local.INFO: Custom Job: App\CustomJobs\Three::executeThree - Status: processing  
[2024-11-14 05:26:15] local.INFO: This message is called from App\CustomJobs\Three::executeThree  
[2024-11-14 05:26:15] local.INFO: Custom Job: App\CustomJobs\Three::executeThree - Status: completed  
```

### Fail

**Example 5**

```php
runBackgroundJob('App\Models\User', 'casts')
```

Output (`background_jobs.log`):

```
[2024-11-14 05:26:57] local.INFO: Custom job: App\Models\User::casts - Status: pending  
[2024-11-14 05:27:00] local.INFO: Custom Job: App\Models\User::casts - Status: processing  
[2024-11-14 05:27:00] local.INFO: Custom Job: App\Models\User::casts - Status: failed  
```

Output (`background_jobs_errors.log`):

```
[2024-11-14 05:27:00] local.ERROR: Failed Custom Job: App\Models\User::casts - Error: Class App\Models\User is invalid.  
```

**Example 6**

```php
runBackgroundJob('App\CustomJobs\None', 'nonExistentMethod')
```

Output (`background_jobs.log`):

```
[2024-11-14 05:30:35] local.INFO: Custom job: App\CustomJobs\None::nonExistentMethod - Status: pending  
[2024-11-14 05:30:35] local.INFO: Custom Job: App\CustomJobs\None::nonExistentMethod - Status: processing  
[2024-11-14 05:30:35] local.INFO: Custom Job: App\CustomJobs\None::nonExistentMethod - Status: failed  
```

Output (`background_jobs_errors.log`):

```
[2024-11-14 05:30:35] local.ERROR: Failed Custom Job: App\CustomJobs\None::nonExistentMethod - Error: Method nonExistentMethod does not exist in class App\CustomJobs\None.  
```

**Example 7**

```php
runBackgroundJob('App\CustomJobs\None', 'executeNone', ['param1'])
```

Output (`background_jobs.log`):

```
[2024-11-14 05:33:41] local.INFO: Custom job: App\CustomJobs\None::executeNone - Status: pending  
[2024-11-14 05:33:46] local.INFO: Custom Job: App\CustomJobs\None::executeNone - Status: processing  
[2024-11-14 05:33:46] local.INFO: Custom Job: App\CustomJobs\None::executeNone - Status: failed  
```

Output (`background_jobs_errors.log`):

```
[2024-11-14 05:33:46] local.ERROR: Failed Custom Job: App\CustomJobs\None::executeNone - Error: Invalid parameters for method executeNone.
```

## Screenshots

![Login Screenshot](https://i.ibb.co/xH7FTH0/Screenshot-from-2024-11-14-03-14-48.png)
![Custom Jobs Screenshot](https://i.ibb.co/rGxfTy1/Screenshot-from-2024-11-14-03-18-09.png)