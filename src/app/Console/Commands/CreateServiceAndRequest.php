<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateServiceAndRequest extends Command
{

    const REQUEST_PATH = 'app/Http/Requests/';
    const SERVICE_PATH = 'app/Services/';
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {serviceName : The name of service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create service and request files';

    protected $type = 'Service';

    private $file_name;

    private $service_namespace;

    private $request_namespace;

    private $plural_filename;

    private $lower_file_name;

    private $service_dir;

    private $request_dir;

    private $create_service_file_name;

    private $list_service_file_name;

    private $update_service_file_name;

    private $delete_service_file_name;

    private $request_file_name;

    private $request_filter_file_name;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->file_name = $this->argument('serviceName');
        $this->plural_filename = Str::plural($this->file_name);
        $this->lower_file_name = Str::snake($this->file_name);

        if (is_null($this->file_name)) {
            $this->error('Service Name invalid');
        }
        if(!file_exists(self::SERVICE_PATH)){
            mkdir(self::SERVICE_PATH);
        }
        if(!file_exists(self::REQUEST_PATH)){
            mkdir(self::REQUEST_PATH);
        }

        $this->service_dir = self::SERVICE_PATH . $this->file_name;
        $this->request_dir = self::REQUEST_PATH . $this->file_name;

        $this->service_namespace = 'App\Services\\' . $this->file_name;
        $this->request_namespace = 'App\Http\Requests\\' . $this->file_name;

        $this->create_service_file_name = $this->service_dir . "/Create" . $this->file_name . 'Service.php';
        $this->list_service_file_name = $this->service_dir . "/List" . $this->plural_filename . 'Service.php';
        $this->update_service_file_name = $this->service_dir . "/Update" . $this->file_name . 'Service.php';
        $this->delete_service_file_name = $this->service_dir . "/Delete" . $this->file_name . 'Service.php';
        $this->request_file_name = $this->request_dir . "/Save" . $this->file_name . 'Request.php';
        $this->request_filter_file_name = $this->request_dir . "/Save" . $this->file_name . 'RequestFilter.php';

        if ($this->isExistFiles()) {
            $this->error('already exist');
            return;
        }
        if (!$this->isExistServiceDir()) {
            mkdir($this->service_dir);
        }

        if (!$this->isExistRequestDir()) {
            mkdir($this->request_dir);
        }

        $this->createCreateServiceFile();
        $this->createListServiceFile();
        $this->createUpdateServiceFile();
        $this->createDeleteServiceFile();
        $this->createRequestFile();
        $this->createRequestFilterFile();
        $this->info('create successfully');
    }

    private function createCreateServiceFile(): void
    {
        $content = "<?php
declare(strict_types=1);
namespace {$this->service_namespace};

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\\{$this->file_name};
use App\Models\Repositories\Contracts\\{$this->file_name}RepositoryInterface;
use App\Http\Requests\\{$this->file_name}\Save{$this->file_name}Request;
use App\Services\Traits\Validatable;

class Create{$this->file_name}Service
{
    use Filterable,
        Validatable;

    private \$repository;
    private \$request;

    public function __construct({$this->file_name}RepositoryInterface \$repository, Save{$this->file_name}Request \$request)
    {
        \$this->repository = \$repository;
        \$this->request    = \$request;

        \$this->setFormRequest(new Save{$this->file_name}Request());
        \$this->init();
    }

    public function init()
    {
        \$this->request->flush();
    }

    public function create(\$inputs = null): {$this->file_name}
    {
        if (is_null(\$inputs)) {
            \$inputs = \$this->request->except('action');
        }
        try {
            return DB::transaction(function () use (\$inputs) {
                \${$this->lower_file_name} = \$this->repository->new(\$inputs);
                \${$this->lower_file_name} = \$this->repository->persist(\${$this->lower_file_name});
                return \${$this->lower_file_name};
            });
        } catch (Throwable \$exception) {
            throw \$exception;
        }
    }
}
";

        file_put_contents($this->create_service_file_name, $content);
    }

    private function createListServiceFile(): void
    {
        $content = "<?php
declare(strict_types=1);
namespace {$this->service_namespace};

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;
use App\Models\Entities\\{$this->file_name};
use App\Models\Repositories\Contracts\\{$this->file_name}RepositoryInterface;
use App\Services\Traits\Conditionable;

class List{$this->plural_filename}Service
{
    use Conditionable;

    private \$repository;
    private \$request;

    public function __construct({$this->file_name}RepositoryInterface \$repository, Request \$request)
    {
        \$this->repository = \$repository;
        \$this->request    = \$request;
    }

    public function find(\$id): ?Entity
    {
        return \$this->repository->find(\$id);
    }

    public function list(\$conditions = null, \$limit = null, \$offset = null): Collection
    {
        if (! is_array(\$conditions)) {
            \$conditions = \$this->conditionQueryToArray(\$conditions);
        }        

        return \$this->repository->list(\$conditions, \$limit, \$offset);
    }
    public function sqlList(\$domain, \$name, array \$parameters = [], array \$conditions = [], array \$orderBy = [], array \$paginate = []): Collection
    {
        return \$this->repository->sqlList(\$domain, \$name, \$parameters, \$conditions, \$orderBy);
    }
    public function paginate(\$conditions = null, int \$perPage = 10): LengthAwarePaginator
    {
        if (! is_array(\$conditions)) {
            \$conditions = \$this->conditionQueryToArray(\$conditions);
        }        

        return \$this->repository->paginate(\$conditions, \$perPage);
    }
}

";

        file_put_contents($this->list_service_file_name, $content);
    }

    private function createUpdateServiceFile(): void
    {
        $content = "<?php
declare(strict_types=1);
namespace {$this->service_namespace};

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\\{$this->file_name};
use App\Models\Repositories\Contracts\\{$this->file_name}RepositoryInterface;
use App\Http\Requests\\{$this->file_name}\Save{$this->file_name}Request;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class Update{$this->file_name}Service
{
    use Filterable,
        Validatable;

    private \$repository;
    private \$request;

    public function __construct({$this->file_name}RepositoryInterface \$repository, Request \$request)
    {
        \$this->repository = \$repository;
        \$this->request    = \$request;

        \$this->setFormRequest(new Save{$this->file_name}Request());
        \$this->init();
    }

    public function init()
    {

        \$this->request->flush();

        \${$this->lower_file_name} = \$this->repository->find(\$this->request->offsetGet('id'));

        \$defaults = \${$this->lower_file_name}->toArray();

        \$this->request->merge(\$defaults);
    }

    public function update(\$inputs = null): {$this->file_name}
    {
        if (is_null(\$inputs)) {
            \$inputs = \$this->request->except('action');
        }
        try {
            return DB::transaction(function () use (\$inputs) {
                \${$this->lower_file_name} = \$this->repository->find(\$this->request->offsetGet('id'));
                \${$this->lower_file_name} = \$this->repository->edit(\${$this->lower_file_name}, \$inputs);
                \${$this->lower_file_name} = \$this->repository->persist(\${$this->lower_file_name});

                return \${$this->lower_file_name};
            });
        } catch (Throwable \$exception) {
            throw \$exception;
        }
    }
}
";

        file_put_contents($this->update_service_file_name, $content);
    }

    private function createDeleteServiceFile(): void
    {
        $content = "<?php
declare(strict_types=1);
namespace {$this->service_namespace};

use Throwable;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\\{$this->file_name};
use App\Models\Repositories\Contracts\\{$this->file_name}RepositoryInterface;

class Delete{$this->file_name}Service
{
    private \$repository;
    private \$request;

    public function __construct({$this->file_name}RepositoryInterface \$repository, Request \$request)
    {
        \$this->repository = \$repository;
        \$this->request    = \$request;
    }

    public function delete(): {$this->file_name}
    {
        try {
            return DB::transaction(function () {
                \${$this->lower_file_name} = \$this->repository->find(\$this->request->offsetGet('id'));
                \$this->repository->delete(\${$this->lower_file_name});

                return \${$this->lower_file_name};
            });
        } catch (Throwable \$exception) {
            throw \$exception;
        }
    }

    public function restore(): {$this->file_name}
    {
        try {
            return DB::transaction(function () {
                \${$this->lower_file_name} = \$this->repository->findOnlyTrashed(\$this->request->offsetGet('id'));
                \$this->repository->restore(\${$this->lower_file_name});

                return \${$this->lower_file_name};
            });
        } catch (Throwable \$exception) {
            throw \$exception;
        }
    }
}
";

        file_put_contents($this->delete_service_file_name, $content);
    }

    private function createRequestFile(): void
    {
        $content = "<?php
declare(strict_types=1);

namespace $this->request_namespace;

use Illuminate\Foundation\Http\FormRequest;

class Save{$this->file_name}Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }

    public function attributes()
    {
        return [

        ];
    }

    public function messages()
    {
        \$message = [

        ];
        return array_merge(parent::messages(), \$message);
    }
}
";
        file_put_contents($this->request_file_name, $content);
    }


    private function createRequestFilterFile(): void
    {
        $content = "<?php
declare(strict_types=1);

namespace $this->request_namespace;

use App\Http\Requests\RequestFilter;

class Save{$this->file_name}RequestFilter extends RequestFilter
{
    public function filterInputs(array \$inputs): array
    {
        \$inputs = parent::filterInputs(\$inputs);

        \$encoding = mb_internal_encoding();

        foreach (\$inputs as \$attribute => \$value) {
            switch (\$attribute) {
                default:
                    break;
            }
            \$inputs[\$attribute] = \$value;
        }

        return \$inputs;
    }
}
";
        file_put_contents($this->request_filter_file_name, $content);
    }

    private function isExistFiles(): bool
    {
        return file_exists($this->create_service_file_name);
    }

    private function isExistServiceDir(): bool
    {
        return file_exists($this->service_dir);
    }

    private function isExistRequestDir(): bool
    {
        return file_exists($this->request_dir);
    }
}
