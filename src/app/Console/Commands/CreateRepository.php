<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepository extends Command
{

    /**
     * @const string repository dir path
     */
    const REPOSITORY_PATH = 'app/Models/Repositories';
    const CONTRACTS_PATH = 'app/Models/Repositories/Contracts';
    const ELOQUENT_PATH = 'app/Models/Repositories/Eloquent';
    const PROVIDER_PATH = 'app/Providers/RepositoryServiceProvider.php';

    const CONTRACTS_NAMESPACE = 'App\Models\Repositories\Contracts';
    const ELOQUENT_NAMESPACE = 'App\Models\Repositories\Eloquent';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repositoryName : The name of repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repository files';

    protected $type = 'Repository';

    private $file_name;

    private $repository_file_name;

    private $interface_file_name;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->file_name = $this->argument('repositoryName');

        if (is_null($this->file_name)) {
            $this->error('Repository Name invalid');
        }

        if (!file_exists(self::REPOSITORY_PATH)) {
            mkdir(self::REPOSITORY_PATH);
        }
        if (!file_exists(self::ELOQUENT_PATH)) {
            mkdir(self::ELOQUENT_PATH);
        }
        if (!file_exists(self::CONTRACTS_PATH)) {
            mkdir(self::CONTRACTS_PATH);
        }

        $this->repository_file_name = self::ELOQUENT_PATH . "/" . $this->file_name . 'Repository.php';
        $this->interface_file_name = self::CONTRACTS_PATH . "/" . $this->file_name . 'RepositoryInterface.php';

        if ($this->isExistFiles()) {
            $this->error('already exist');
            return;
        }

        $this->createRepositoryFile();
        $this->createInterFaceFile();
        $this->updateRepositoryServiceProvider();

        $this->info('create successfully');
    }

    private function createRepositoryFile(): void
    {
        $content = "<?php\ndeclare(strict_types=1);\n\nnamespace "
            . self::ELOQUENT_NAMESPACE
            . ";\n\nuse Throwable;\nuse Exception;\nuse DB;\nuse Illuminate\Database\Eloquent\Builder;\nuse Illuminate\Support\Collection;\nuse Illuminate\Pagination\LengthAwarePaginator;\nuse App\Models\Entities\\"
            . $this->file_name . ";\nuse App\Models\Repositories\Contracts\\" . $this->file_name
            . "RepositoryInterface;\nuse App\Models\Repositories\Eloquent\Repository;\n\nfinal class "
            . $this->file_name . "Repository extends Repository implements $this->file_name" . "RepositoryInterface\n{\n    protected static \$model = "
            . $this->file_name . "::class;\n}\n";
        file_put_contents($this->repository_file_name, $content);
    }

    private function createInterFaceFile(): void
    {
        $content = "<?php\ndeclare(strict_types=1);\n\nnamespace "
            . self::CONTRACTS_NAMESPACE
            . ";\n\nuse Illuminate\Support\Collection;\nuse Illuminate\Pagination\LengthAwarePaginator;\nuse App\Models\Entities\\"
            . $this->file_name . ";\n\ninterface " . $this->file_name . "RepositoryInterface extends RepositoryInterface\n{\n}\n";
        str_replace("/", "\\", $content);
        file_put_contents($this->interface_file_name, $content);
    }

    private function updateRepositoryServiceProvider(): void
    {
        $content = file_get_contents(self::PROVIDER_PATH);

        $interface = self::CONTRACTS_NAMESPACE . '\\' . $this->file_name . 'RepositoryInterface';
        $repository = self::ELOQUENT_NAMESPACE . '\\' . $this->file_name . 'Repository';
        $interface_class = $this->file_name . 'RepositoryInterface::class';
        $repository_class = $this->file_name . 'Repository::class';

        $content = str_replace('// add Interface', 'use ' . $interface . ";\n" . '// add Interface', $content);
        $content = str_replace('// add Repository', 'use ' . $repository . ";\n" . '// add Repository', $content);
        $content = str_replace(
            '// add pair Interface Repository',
            $interface_class . ' => ' . $repository_class . ",\n"
                . '            // add pair Interface Repository',
            $content
        );

        file_put_contents(self::PROVIDER_PATH, $content);
    }

    private function isExistFiles(): bool
    {
        return file_exists($this->repository_file_name) && file_exists($this->interface_file_name);
    }
}
