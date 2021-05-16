# Version Diff Finder

Codeigniter Dashboard to view the difference between two version code in PHP using LCM(Longest Common Subsequence) algorithm.

## Installation

clone the repo

```bash
git clone https://github.com/aghilanbaskar/codeigniter-diff.git

cd codeigniter-diff
```
Change the Base URL in the file application/config/config.php

```diff
- $config['base_url']='https://'.$_SERVER['HTTP_HOST'];
+ $config['base_url']='http://localhost/codeigniter-diff/;
```

## Local Usage

start Apache webserver in local XAMPP

go to the file in the browser [http://localhost/codeigniter-diff/dashbaord](http://localhost/codeigniter-diff/dashbaord)

## Demo
Demo Link : [https://astra-task.herokuapp.com/](https://astra-task.herokuapp.com/)

## License
[MIT](https://choosealicense.com/licenses/mit/)
