<?php
namespace App\Classes\Helpers;

use App\Models\Account;
use App\Models\Model;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class TenantHelper{
    /**
     * Creates a new database user and a new database for the new $Account 
     * and also adds the tables to the db and sets the permissions
     * 
     * @param Account $Account
     * @return boolean
     */
    public static function newTenant(Account $Account){
        $rand = str_random(8);
        $dbSufix= 'vend_'.$rand;
        $dbUserSufix= 'vend_'.$rand;

//        $newDBName=  str_replace('-','', str_slug($Account->company)). $dbSufix;
//        $newDBUser= str_slug($Account->company). '-' . $dbUserSufix;
        $newDBName=  $dbSufix;
        $newDBUser= $dbUserSufix;
        $newDBPawd= str_random(16);
        
        //save the new db name into the the tenant account
        $Account->db_name= $newDBName;
        $Account->db_user= $newDBUser;
        $Account->db_password= $newDBPawd;
        $Account->save();
        
        //create new db user
        static::createDBUser($newDBUser, $newDBPawd);
        
        //create new data base
        static::createDatabase($newDBName);
        
        //set grant options for the new user to the new db
        static::setDBPermission($newDBUser, $newDBName);
        
        //add tables to the new db
        static::addTables($newDBName, $newDBUser, $newDBPawd);
        
        return true;
    }
    /**
     * Create a database user with the given user:password
     * @param type $user
     * @param type $password
     */
    public static function createDBUser($user, $password){
        \DB::statement("CREATE USER '". $user ."'@'localhost' IDENTIFIED BY '". $password ."'");
    }
    
    /**
     * Create a database with the given name
     * @param type $database name of the database
     */
    public static function createDatabase($database){
        \DB::statement("CREATE DATABASE ". $database);
    }
    
    /**
     * Set CRUD an migrations related Grants to the given user to the given database
     * @param type $user 
     * @param type $database 
     */
    public static function setDBPermission($user, $database){
        \DB::statement("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, REFERENCES, INDEX, CREATE TEMPORARY TABLES, LOCK TABLES, SHOW VIEW, CREATE ROUTINE, EXECUTE, EVENT, TRIGGER ON ". $database .".* TO '". $user ."'@'localhost'");
    }
    
    /**
     * Add the tables to the fiven database by running the migration
     * 
     * @param type $database
     * @param type $user
     * @param type $password
     */
    public static function addTables($database, $user, $password){

        // Clear the configuration cache
        Artisan::call('config:clear');

        //set the 'tenant' database connection settings to the specified data
        config([
            'database.connections.tenant.username' => $user,
            'database.connections.tenant.password' => $password,
            'database.connections.tenant.database' => $database
        ]);
        
        //run the migrations to add the tables to the given database
        Artisan::call('migrate', ['--database' => 'tenant', '--force' => true]);
                
    }

    /**
     * Run the migration on the db corresponding to the givent tenant account.
     * When no tenant account is supplied, it runs the migration on all tenants dbs
     * @param type $TenantAccount
     */
    public static function migrateTenant($TenantAccount=null){
        //run the migration only on the supplied tenant's db
        if(isset($TenantAccount)){
            static::migrate($TenantAccount);
        }
        //run migration on all tenant's dbs when no tenant account is provided
        else{
            $accounts= Account::all();
            foreach($accounts as $Account){
                static::migrate($Account);
            }
        }
    }
    
    /**
     * Set the tenant db settings and run the migration
     * @param type $TenantAccount
     */
    private static function migrate($TenantAccount){

        // Logging
        echo 'Migrating tenant: '.$TenantAccount->db_name."\n";

        // Clear the configuration cache
        Artisan::call('config:clear');

        //set the 'tenant' database connection settings to the specified data
        config([
            'database.connections.tenant.username' => $TenantAccount->db_user,
            'database.connections.tenant.password' => $TenantAccount->db_password,
            'database.connections.tenant.database' => $TenantAccount->db_name
        ]);
        
        //run the migrations to add the tables to the given database
        Artisan::call('migrate', ['--database' => 'tenant','--path' => 'database/migrations/tenants' ,'--force' => true]);
    }
}
