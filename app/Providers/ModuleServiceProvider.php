<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các service nếu cần
    }

    public function boot()
    {
        $this->loadModuleRoutes(); // Đăng ký route cho tất cả các module
        $this->loadModuleViews(); // Đăng ký view cho tất cả các module
        $this->loadMigration(); // Đăng ký migration cho tất cả các module
      
        // Đăng ký loadControllersFrom cho tất cả các module
    }

    protected function loadMigration()
    {
        $this->loadModuleFiles('Migrations', function ($migrationPath) {
            $this->loadMigrationsFrom($migrationPath);
        });
    }

    protected function loadModuleRoutes()
    {
        $this->loadModuleFiles('Routes', function ($routeFile, $moduleName) {
            Route::group(['namespace' => "App\\Modules\\$moduleName\\Controllers"], function () use ($routeFile) {
                require $routeFile; // Đảm bảo rằng $routeFile chỉ định đúng đường dẫn
            });
        });
    }
    

    protected function loadSubModuleRoutes($moduleName) {
        $modulesPath = base_path("app/Modules/$moduleName");
        $musicCompanyRoutesPath = "$modulesPath/MusicCompany/Routes";
    
        if (is_dir($musicCompanyRoutesPath)) {
            foreach (scandir($musicCompanyRoutesPath) as $subModule) {
                if ($subModule === '.' || $subModule === '..') {
                    continue;
                }
    
                $subModulePath = "$musicCompanyRoutesPath/$subModule";
                if (is_dir($subModulePath)) {
                    // Kiểm tra file routes trong thư mục MusicCompany
                    $routeFile = "$subModulePath/web.php";
                    if (file_exists($routeFile)) {
                        // Sử dụng đúng namespace
                        Route::group(['namespace' => "App\\Modules\\$moduleName\\MusicCompany\\Controllers"], function () use ($routeFile) {
                            require $routeFile;
                        });
                    }
                }
            }
        }
    }
    
    
    
    protected function loadModuleViews()
    {
        $this->loadModuleFiles('Views', function ($viewPath, $module) {
            $this->loadViewsFrom($viewPath, $module); // Sử dụng tên module làm hint
        });
    }

    protected function loadModuleFiles($subDir, $callback)
    {
        $modulesPath = base_path('app/Modules');

        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $modulePath = "$modulesPath/$module";
                if (is_dir($modulePath)) {
                    // Kiểm tra các thư mục con
                    $this->checkSubModules($modulePath, $subDir, $callback, $module);
                    
                    // Kiểm tra thư mục chính (trong trường hợp không có submodule)
                    $targetPath = "$modulePath/$subDir";
                    if (is_dir($targetPath)) {
                        if ($subDir === 'Routes') {
                            $routeFile = "$targetPath/web.php";
                            if (file_exists($routeFile)) {
                                $callback($routeFile, $module);
                            }
                        } else {
                            $callback($targetPath, $module);
                        }
                    }
                }
            }
        }
    }

    protected function checkSubModules($modulePath, $subDir, $callback, $module)
    {
        foreach (scandir($modulePath) as $subModule) {
            if ($subModule === '.' || $subModule === '..') {
                continue;
            }

            $subModulePath = "$modulePath/$subModule";
            if (is_dir($subModulePath)) {
                // Kiểm tra thư mục con cho submodule
                $targetPath = "$subModulePath/$subDir";
                if (is_dir($targetPath)) {
                    if ($subDir === 'Routes') {
                        $routeFile = "$targetPath/web.php";
                        if (file_exists($routeFile)) {
                            $callback($routeFile, "$module.$subModule");
                        }
                    } else {
                        $callback($targetPath, "$module.$subModule");
                    }
                }
            }
        }
    }
}
