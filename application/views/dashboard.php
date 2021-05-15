<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard | File Diff</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://www.getastra.com/images/favicon.png" rel="shortcut icon" type="image/x-icon">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script type='text/javascript'>const baseURL= "<?php echo base_url();?>";</script>
  <style>
  .fa-plus{
    color: green;
  }
  .fa-minus{
    color: red;
  }
  .fa-edit{
    color: #B58503;
  }
  </style>
</head>
<body>

<div class="container my-3">
  <div id="dashboard-count" class="row text-light">
    <!-- added count -->
    <div class="col-xl-4 col-lg-3">
      <div class="card card-inverse card-danger">
        <div class="card-block bg-success">
          <h6 class="text-uppercase text-center" >Added Files</h6>
          <h1 class="added-count display-1 text-center">
            <!-- loader -->
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </h1>
        </div>
      </div>
    </div>
    <!-- deleted count -->
    <div class="col-xl-4 col-lg-3">
      <div class="card card-inverse card-danger">
        <div class="card-block bg-danger">
          <h6 class="text-uppercase text-center">Deleted Files</h6>
          <h1 class="deleted-count display-1 text-center">
            <!-- loader -->
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </h1>
        </div>
      </div>
    </div>
    <!-- changed files count -->
    <div class="col-xl-4 col-lg-3">
      <div class="card card-inverse card-danger">
        <div class="card-block bg-warning">
          <h6 class="text-uppercase text-center">Changed Files</h6>
          <h1 class="changed-count display-1 text-center">
            <!-- loader -->
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </h1>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-3">
    <div id="added-files" class="col-12 d-none">
      <h4 class="text-center">Added Files</h4>
      <ul class="list-group">
      </ul>
    </div>
  </div>
  <div class="row mt-3">
    <div id="deleted-files" class="col-12 d-none">
      <h4 class="text-center">Deleted Files</h4>
      <ul class="list-group">
      </ul>
    </div>
  </div>
  <div class="row mt-3">
    <div id="changed-files" class="col-12 d-none">
      <h4 class="text-center">Changed Files</h4>
      <ul class="list-group">
      </ul>
    </div>
  </div>
</div>

</body>
<script type="text/javascript" src="<?=base_url();?>assets/js/dashboard.js?v1.0"></script>
</html>
