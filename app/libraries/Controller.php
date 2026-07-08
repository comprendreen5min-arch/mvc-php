<?php
class Controller
{
    /**
     * Load a model
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        $modelFile = '../app/models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;

            // Instantiate the model
            if (class_exists($model)) {
                return new $model();
            } else {
                die("Model class '$model' does not exist in file.");
            }
        } else {
            die("Model file '$model.php' does not exist.");
        }
    }

    /**
     * Load a view
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewFile = '../app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Extract the data array into variables
            extract($data);

            // Get the content of the view file
            $viewContent = file_get_contents($viewFile);

            // Compile the view content
            $compiledContent = $this->compileView($viewContent, $data);

            // Create output using include
            ob_start();

            // Write compiled content to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'view_');
            if ($tempFile === false) {
                 die("Could not create temporary file for view.");
            }
            file_put_contents($tempFile, $compiledContent);

            // Include the temporary file
            include $tempFile;

            // Delete the temporary file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            $output = ob_get_clean();
            echo $output;
        } else {
            // View does not exist
            http_response_code(404);
            die('The requested view does not exist.');
        }
    }

    /**
     * Compile custom blade-like syntax
     * @param string $content
     * @param array $data
     * @return string
     */
    private function compileView($content, $data)
    {
        // Handle PHP variables and expressions in {{ }}
        $content = preg_replace_callback('/\{\{\s*(.*?)\s*\}\}/', function ($matches) {
            $expression = trim($matches[1]);
            // Use null coalescence for safety
            return '<?php echo htmlspecialchars((' . $expression . ') ?? "", ENT_QUOTES, \'UTF-8\'); ?>';
        }, $content);
    
        // Handle control structures
        $content = preg_replace('/@if\s*\((.*?)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content);
    
        // Handle loops
        $content = preg_replace('/@foreach\s*\((.*?)\s+as\s+(.*?)\)/', '<?php foreach($1 as $2): ?>', $content);
        $content = str_replace('@endforeach', '<?php endforeach; ?>', $content);
    
        $content = preg_replace('/@for\s*\((.*?)\)/', '<?php for($1): ?>', $content);
        $content = str_replace('@endfor', '<?php endfor; ?>', $content);
    
        $content = preg_replace('/@while\s*\((.*?)\)/', '<?php while($1): ?>', $content);
        $content = str_replace('@endwhile', '<?php endwhile; ?>', $content);
    
        return $content;
    }
    


    // Helper function to safely handle variables if needed internally
    private function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
