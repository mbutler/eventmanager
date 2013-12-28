<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: functions-file.php



============================================================================= */


// Returns array of directories inside given path 
function pem_get_directory_list($path)
{
   if (is_dir($path))
   {
      if ($dirhandle = opendir($path)) 
      {
         while (($file = readdir($dirhandle)) !== false) 
         {
            if (filetype($path . $file) == "dir" AND $file != ".." AND $file != ".")
            {
               $directories[] = $file;
            }
         }
         closedir($dirhandle);
         sort($directories);
         return $directories;
      }
      return false;
   }
} // END pem_get_directory_path

// Returns array of files inside given path 
function pem_get_file_list($path)
{
   if (is_dir($path))
   {
      if ($dirhandle = opendir($path)) 
      {
         while (($file = readdir($dirhandle)) !== false) 
         {
            if (filetype($path . $file) == "file")
            {
               $files[] = $file;
            }
         }
         closedir($dirhandle);
         sort($files);
         return $files;
      }
      return false;
   }
} // END pem_get_file_list


?>