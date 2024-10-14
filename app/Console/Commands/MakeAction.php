<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new action class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Actions/{$name}.php");

        // Ensure the Actions directory and subdirectories exist
        $directory = dirname($path);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Get the class name without the directory structure
        $className = basename(str_replace('\\', '/', $name));
        $namespace = 'App\\Actions';

        // Remove the class name from the name argument to get the folder structure
        $folderName = trim(str_replace($className, '', $name), '\\');
        if (!empty($folderName)) {
            $namespace .= '\\' . $folderName;
        }

        // Create the file with a basic template
        $template = "<?php\n\nnamespace {$namespace};\n\nclass {$className}\n{\n    public function sampleFunction()\n    {\n        // Action logic here\n    }\n}\n";

        if (File::exists($path)) {
            $this->error("Action class {$className} already exists!");
            return 1;
        }

        File::put($path, $template);

        $this->info("Action class {$className} created successfully.");
        return 0;
    }
}
