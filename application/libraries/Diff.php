<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Diff
{
    public function compareDir($dir1, $dir2)
    {
        if (!is_dir($dir1) || !is_dir($dir2))
        {
            return array(
                'status' => false,
                'message' => 'Folder should be a directory'
            );
        }
        //get all files in folder in full path
        $files_dir1 = $this->getfolderTreeView($dir1);
        $files_dir2 = $this->getfolderTreeView($dir2);

        //files available in both version
        $files = array_intersect($files_dir1, $files_dir2);

        //files exist in version 1 but not in version 2 - deleted files
        $added_files = array_values(array_diff($files_dir2, $files));

        //files exist in version 2 but not in version 1 - added files
        $deleted_files = array_values(array_diff($files_dir1, $files));

        // check files in two folder and check both files differs
        $fileswithdiff = $this->getFilesWithDifference($files, $dir1, $dir2);

        $changed_files = array();
        foreach ($fileswithdiff as $i => $file)
        {
            $changed_files[] = $this->getFileDiff($file, $dir1, $dir2);
        }
        $result = array(
            'added' => $added_files,
            'deleted' => $deleted_files,
            'changed' => $changed_files
        );
        return $result;
    }

    private function getfolderTreeView($dir)
    {
        $files = [];
        foreach (array_diff(scandir($dir) , array(
            '..',
            '.'
        )) as $filename)
        { // .. & . to avoid current and parent directory
            $filePath = $dir . '/' . $filename;
            if (is_file($filePath))
            {
                $files[] = $filename;
                continue;
            }
            if (is_dir($filePath))
            {
                foreach ($this->getfolderTreeView($filePath) as $subfilename)
                { // recursive call for subdirectory
                    $files[] = $filename . '/' . $subfilename;
                }
            }
        }
        return $files;
    }

    private function getFilesWithDifference($files, $dir1, $dir2)
    {
        $fileswithdiff = array();
        foreach ($files as $file)
        {
            // take hash of two files in both dir to check is same or not
            if (hash_file('crc32', $dir1 . '/' . $file) != hash_file('crc32', $dir2 . '/' . $file))
            {
                $fileswithdiff[] = $file;
            }
        }
        return $fileswithdiff;
    }

    private function getFileDiff($file, $dir1, $dir2)
    {
        $file_v1 = file($dir1 . '/' . $file, FILE_IGNORE_NEW_LINES);
        $file_v2 = file($dir2 . '/' . $file, FILE_IGNORE_NEW_LINES);

        $start = 0;
        $file_v1_len = count($file_v1) - 1;
        $file_v2_len = count($file_v2) - 1;

        /*
        Implemented LCM without removing start and end same line which result in max timeout and max space issue
        iterating the common lines in start and end of files for space and time complexity enhancement to implement LCM algorithm
        */
        while ($file_v1[$start] == $file_v2[$start] && ($start <= $file_v1_len && $start <= $file_v2_len))
        { //omitting same lines in start
            $start++;
        }
        while ($file_v1[$file_v1_len] == $file_v2[$file_v2_len] && ($file_v1_len >= $start && $file_v2_len >= $start))
        { //omitting same lines in end
            $file_v1_len--;
            $file_v2_len--;
        }

        $length1 = $file_v1_len - $start + 1;
        $length2 = $file_v2_len - $start + 1;

        /*
          LCM algorithm for git diff finding
          https://www.geeksforgeeks.org/longest-common-subsequence-dp-4/
          https://blog.robertelder.org/diff-algorithm/
        */
        for ($i = 0;$i <= $length1;$i++)
        {
            for ($j = 0;$j <= $length2;$j++)
            {
                if ($i == 0 || $j == 0)
                {
                    $LCM[$i][$j] = 0;
                }
                else if ($file_v1[$i + $start - 1] == $file_v2[$j + $start - 1])
                {
                    $LCM[$i][$j] = $LCM[$i - 1][$j - 1] + 1;
                }
                else
                {
                    $LCM[$i][$j] = max($LCM[$i - 1][$j], $LCM[$i][$j - 1]);
                }
            }
        }

        $addedLineNo = array();
        $deletedLineNo = array();

        // iterate from last i,j to start to find the added and deleted index.
        $i = $length1 + 1;
        $j = $length2 + 1;

        while ($i > 0 || $j > 0)
        {
            if ($i > 0 && $j > 0 && $file_v1[$i + $start - 1] == $file_v2[$j + $start - 1])
            {
                // line not changed
                $i--;
                $j--;
            }
            elseif ($j > 0 && $LCM[$i][$j] == $LCM[$i][$j - 1])
            {
                // line added
                $addedLineNo[] = $j + $start - 1;
                $j--;
            }
            else
            {
                // line deleted
                $deletedLineNo[] = $i + $start - 1;
                $i--;
            }
        }

        $v1 = '';
        $v2 = '';
        foreach ($file_v1 as $i => $line)
        {
            if (in_array($i, $deletedLineNo))
            {
                $v1 .= '<tr class="table-danger"><td>' . htmlspecialchars($line) . '</td></tr>';
            }
            else
            {
                $v1 .= '<tr class="table-light"><td>' . htmlspecialchars($line) . '</td></tr>';
            }
        }
        foreach ($file_v2 as $i => $line)
        {
            if (in_array($i, $addedLineNo))
            {
                $v2 .= '<tr class="table-success"><td>' . htmlspecialchars($line) . '</td></tr>';
            }
            else
            {
                $v2 .= '<tr class="table-light"><td>' . htmlspecialchars($line) . '</td></tr>';
            }
        }
        return array(
            'file' => $file,
            'v1' => $v1,
            'v2' => $v2,
            'addition' => COUNT($addedLineNo) ,
            'deletion' => COUNT($deletedLineNo)
        );
    }
}

?>
