<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Model;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class UpdateSuperUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superuser:update {option}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->argument('option') == 'permissions'){    
            $accounts= Account::all();
            $this->updateTenants($accounts);            
        }
    }
    
    public function updateTenants($accounts){                    
        foreach($accounts as $Account){
            $this->update($Account);
        }        
    }
    
    private function update($TenantAccount){

        // Clear the configuration cache
        Artisan::call('config:clear');

        if($TenantAccount->db_user !='' && $TenantAccount->db_password != '' && $TenantAccount->db_name){
            //set the 'tenant' database connection settings to the specified data                
            Config::set('database.connections.tenant.username', $TenantAccount->db_user);
            Config::set('database.connections.tenant.password', $TenantAccount->db_password);
            Config::set('database.connections.tenant.database', $TenantAccount->db_name);
            Config::set('database.default', 'tenant');
            \DB::purge('tenant');
            \DB::reconnect('tenant');

            $Role= UserRole::where('name','Vendata Super User')->first();
            \DB::delete('delete from user_role_permissions where user_role_id = '. $Role->id);
            $models= Model::all();
            $bar= $this->output->createProgressBar(count($models));
            foreach($models as $Model){
                $Permission= new UserRolePermission();
                $Permission->user_role_id= $Role->id;
                $Permission->model_id= $Model->id;
                $Permission->save_action=1;
                $Permission->read_action=1;
                $Permission->delete_action=1;
                $Permission->save();
                $bar->advance();                
            }
            $bar->finish();
            $this->info('');
            $this->info('Permissions updated');
        }
    }
}
