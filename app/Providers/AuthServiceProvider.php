<?php

namespace App\Providers;

use App\Classes\Helpers\EcommercePlatforms\Magento\Customer;
use App\Models\Account;
use App\Models\AddressType;
use App\Models\Attribute;
use App\Models\Country;
use App\Models\DataType;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductSupplier;
use App\Models\Relation;
use App\Models\RelationAddress;
use App\Models\RelationContact;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Policies\AccountPolicy;
use App\Policies\AddressTypePolicy;
use App\Policies\AttributePolicy;
use App\Policies\CountryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\DataTypePolicy;
use App\Policies\OrderHistoryPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductAttributeValuePolicy;
use App\Policies\ProductImagePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductSupplierPolicy;
use App\Policies\RelationAddressPolicy;
use App\Policies\RelationContactPolicy;
use App\Policies\RelationPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Product::class => ProductPolicy::class,
        ProductImage::class => ProductImagePolicy::class,
        ProductAttributeValue::class => ProductAttributeValuePolicy::class,
        ProductSupplier::class => ProductSupplierPolicy::class,
        AddressType::class => AddressTypePolicy::class,
        Attribute::class => AttributePolicy::class,
        Relation::class => RelationPolicy::class,
        RelationAddress::class => RelationAddressPolicy::class,
        RelationContact::class => RelationContactPolicy::class,
        Supplier::class => SupplierPolicy::class,
        Warehouse::class => WarehousePolicy::class,
        Order::class => OrderPolicy::class,
        DataType::class => DataTypePolicy::class,
        Country::class => CountryPolicy::class,
        Customer::class => CustomerPolicy::class,
        Account::class => AccountPolicy::class,
        OrderHistory::class => OrderHistoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
