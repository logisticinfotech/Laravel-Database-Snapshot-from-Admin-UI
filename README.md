## Github Link 
	https://github.com/spatie/laravel-db-snapshots

## Create Project 
	laravel new db-snapshot

	Database Snapshot is use for quickly dump and load databases in laravel.You can create sql file or compressed sql file.there are many commnad like list all snapshot files, Load spanshot files, Delete snapshot files and download snapshot file.There are several events like Creating Snapshot ,Created Snapshot, Loading Snapshot, Loaded Snapshot, Deleting Snapshot, Deleted Snapshot fired which can be used to perform some logic of your own.

## Install the package via Composer
	composer require spatie/laravel-db-snapshots

## If Laravel 5.4 or below you must install this service provider to config/app.php otherwise will autoregister the service provider
	
	'providers' => [
		// ...
		Spatie\DbSnapshots\DbSnapshotsServiceProvider::class,
	];

## In app/config/filesystems.php file specify a disk named snapshots on which snapshots will be saved.

	// ...
	'disks' => [
		// ...
		'snapshots' => [
			'driver' => 'local',
			'root' => storage_path('snapshots'),
		],
	// ...    


## This is Optionally, you may publish the configuration file using this command	
	php artisan vendor:publish --provider="Spatie\DbSnapshots\DbSnapshotsServiceProvider" --tag="config"  
														//publish configutation file will be store inside confing folder

## Environment configuration for database connection

	DB_HOST=YOUR_HOST
	DB_DATABASE=YOUR_DATABASE
	DB_USERNAME=YOUR_USERNAME
	DB_PASSWORD=YOUR_PASSWORD

## Creating Migrations and Run Migration Command

	php artisan migrate:make create_users_table			// Create users table 
	php artisan migrate									// Run migration

## Create Seeder and Run Seeder Command

	php artisan make:seeder UsersTableDataSeeder 		// Create users table seeder 
	php artisan db:seed --class=UsersTableDataSeeder 	// Run seeder for users table

## Create Database Snapshot

	$file_name = str_replace($search, $replace, $file_name); // File name not conatin whitespace or pipeline
	Artisan::call('snapshot:create '.$file_name); 		// file name is optional. If you don't pass a file name the current date time will be used as file name

## To list of all snapshot file

	Artisan::call('snapshot:list');
	$snapshot_data = Artisan::output(); // get artisan output
	$snapshot_data = array_filter(explode("\n", $snapshot_data));  // Get File name, date and size
	$snapshot_list = [];
	if(count($snapshot_data) != 1) {
		foreach ($snapshot_data as $index =>$snapshot_list_row) {
			if( $snapshot_list_row[0] != "+" ) {               
				if( $index != 1 ) {                    
					$snapshot_list_column = array_map('trim', array_filter(explode("|", $snapshot_list_row)));	                
					$snapshot_list[$index]["Name"] = $snapshot_list_column[1];            	// Get File Name      
					$snapshot_list[$index]["date"] = $snapshot_list_column[2];				// Get Date 
					$snapshot_list[$index]["size"] = $snapshot_list_column[3];	            // Get File size                  
				} 
			}
		} 
	}

## Delete Snapshot file
	Artisan::call('snapshot:delete '.$file_name); 		// pass file name to delete that file

## Download path of snapshot file
	storage_path("snapshots/".$filename.'.sql'); 		// get download path where sql file is store

## Some useful console commands for db snapshot

	php artisan snapshot:create file-name   			// create snapshot with given name
	php artisan snapshot:create							// current date time used for file name to create snapshot
	php artisan snapshot:create file-name --compress 	// create compressed snapshots 
	php artisan snapshot:load file-name					// Load Snapshot
	php artisan snapshot:load file-name --connection=connectionName  
														//Specify connection name when use multiple db connection
	php artisan snapshot:list							// List of all snapshots
	php artisan snapshot:delete file-name 				// Delete snapshot 

## Some useful events

	Spatie\DbSnapshots\Events\CreatingSnapshot  		// Event will be fired before a snapshot is created
	Spatie\DbSnapshots\Events\CreatedSnapshot  			// Event will be fired after a snapshot has been created
	Spatie\DbSnapshots\Events\LoadingSnapshot  			// Event will be fired before a snapshot is loaded
	Spatie\DbSnapshots\Events\LoadedSnapshot  			// Event will be fired after a snapshot has been loaded
	Spatie\DbSnapshots\Events\DeletingSnapshot  		// Event will be fired before a snapshot is deleted
	Spatie\DbSnapshots\Events\DeletedSnapshot  			// Event will be fired after a snapshot has been deleted