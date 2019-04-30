<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## Laravel Database Snapshot Admin UI

Laravel Database Snapshot Admin UI is one type of admin panel in which you can take a snapshot of the database, show previously taken database snapshot list, download snapshot SQL file and delete the snapshot file. 

- **Create a Laravel Project**
		
		Laravel new db-snapshot-admin-UI 

- **Install Database Snapshot Package Via Composer**
		
		composer require spatie/laravel-db-snapshots

- **Service Provider Configuration**
		
		If Laravel 5.4 or below version you must add below code otherwise it will be auto-registered.
		Open the file config/app.php and then add following service provider
		
			```
			'providers' => [
				// ...
				Spatie\DbSnapshots\DbSnapshotsServiceProvider::class,
			];
			```

- **Filesystem Configuration**
		
		In config/filesystems.php add following code for creating snapshot disk on which all snapshots will be saved. You can change the driver and root values. 
		
			```
			// ...
			'disks' => [
				// ...
				'snapshots' => [
					'driver' => 'local',
					'root' => storage_path('snapshots'),
				],
			// ... 
			```

- **Publish The Configuration File**
		
		This is optional, you may publish the configuration file using this command to customize your setting.
			php artisan vendor:publish --provider="Spatie\DbSnapshots\DbSnapshotsServiceProvider" --tag="config"

- **Create an admin login using laravel auth**
		
		php artisan make:auth

- **Database configuration**
		
		In Env file, specify the value of host, database name, user name, password.
		
			```
			DB_HOST=YOUR_HOST
			DB_DATABASE=YOUR_DATABASE
			DB_USERNAME=YOUR_USERNAME
			DB_PASSWORD=YOUR_PASSWORD
			```

- **Create Migrations**

- **Create Database Snapshot**
		
		```
		$search = ["_"];
		$replace = ["-"];
		$file_name = str_replace($search, $replace, $request->snapshot_name).'_'.Carbon::now()->format('Y-m-d.H:i:s');
		Artisan::call('snapshot:create '.$file_name);
		```

- **Snapshots List**

	````
 	Artisan::call('snapshot:list');
   	$snapshot_data = Artisan::output();
  	$snapshot_data = array_filter(explode("\n", $snapshot_data));
   	$snapshot_list = new Collection;
	if(count($snapshot_data) != 1) {
       foreach ($snapshot_data as $index =>$snapshot_list_row) {
           if( $snapshot_list_row[0] != "+" ) {               
               if( $index != 1 ) {                    
                   $snapshot_list_column = array_map('trim', array_filter(explode("|", $snapshot_list_row)));
                   $file_name = $snapshot_list_column[1];
                   $name = explode("_", $snapshot_list_column[1]);
                   $date = $snapshot_list_column[2];
                   $size = $snapshot_list_column[3];
                   $snapshot_list->push([                       
                       'name'      => $name[0],
                       'date'      => $date,
                       'size'      => $size,
                       'action'    => "<a href='".route('DBSnapshotDownload', $file_name)."' class='text-info'><i class='fa fa-download'></i></a>&nbsp;&nbsp;<a href='".route('DBSnapshotDelete', $file_name)."' onclick=\"return confirm('Are you sure you want to delete this item?');\" class='text-danger'><i class='fa fa-trash'></i></a>"
                   ]);                                
               } 
           }
       } 
    }
	````

- **Download Snapshot file**
	
	return response()->download(storage_path("snapshots/".$snapshot_name.'.sql'));

- **Delete Snapshot file**
	
	Artisan::call('snapshot:delete '.$snapshot_name);

- **Some useful console commands for database snapshot**
	
	```
	php artisan snapshot:create file-name   			// create snapshot with given name
	php artisan snapshot:create							// current date time used for file name to create snapshot
	php artisan snapshot:create file-name --compress 	// create compressed snapshots 
	php artisan snapshot:load file-name					// Load Snapshot
	php artisan snapshot:load file-name --connection=connectionName  
														//Specify connection name when use multiple db connection
	php artisan snapshot:list							// List of all snapshots
	php artisan snapshot:delete file-name 				// Delete snapshot 
	```

- **Some useful events**
	
	```
	Spatie\DbSnapshots\Events\CreatingSnapshot  		// Event will be fired before a snapshot is created
	Spatie\DbSnapshots\Events\CreatedSnapshot  			// Event will be fired after a snapshot has been created
	Spatie\DbSnapshots\Events\LoadingSnapshot  			// Event will be fired before a snapshot is loaded
	Spatie\DbSnapshots\Events\LoadedSnapshot  			// Event will be fired after a snapshot has been loaded
	Spatie\DbSnapshots\Events\DeletingSnapshot  		// Event will be fired before a snapshot is deleted
	Spatie\DbSnapshots\Events\DeletedSnapshot  			// Event will be fired after a snapshot has been deleted
	```

[Laravel DB Snapshot Package Github Link](https://github.com/spatie/laravel-db-snapshots)