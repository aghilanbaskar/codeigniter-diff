$(document).ready( () => {
  const renderInitialUi = (data) => {
    $('#dashboard-count .added-count').text(data.added.length);
    $('#dashboard-count .deleted-count').text(data.deleted.length);
    $('#dashboard-count .changed-count').text(data.changed.length);
    let addedList = '',deletedList = '',changedList='',diffList='';
    data.added.forEach((item, i) => {
      addedList+=`<li class="list-group-item"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;${item}</li>`;
    });
    data.deleted.forEach((item, i) => {
      deletedList+=`<li class="list-group-item"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;${item}</li>`;
    });
    data.changed.forEach((item, i) => {
      changedList+=`<li class="list-group-item"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;${item.file}</li>`;
      diffList+=`<div class="col-12 mt-3">
                  <h4 class="text-left">${item.file} | <i class="fa fa-minus" aria-hidden="true"></i> ${item.deletion} Deletion | <i class="fa fa-plus" aria-hidden="true"></i> ${item.addition} Addition</h4>
                </div>
                <div class="row">
                  <div class="col-6">
                    <table class="table table-sm table-borderless">
                      <tbody>
                        ${item.v1}
                      </tbody>
                    </table>
                  </div>
                  <div class="col-6">
                      <table class="table table-sm table-borderless">
                        <tbody>
                          ${item.v2}
                        </tbody>
                      </table>
                    </div>
                </div>`;
    });
    $('#added-files ul').html(addedList);
    $('#deleted-files ul').html(deletedList);
    $('#changed-files ul').html(changedList);
    $('#diff-files').html(diffList);
    if (data.added.length>0)$('#added-files').removeClass("d-none");
    if (data.deleted.length>0)$('#deleted-files').removeClass("d-none");
    if (data.changed.length>0)$('#changed-files').removeClass("d-none");
  };
  const getDashboardData = () => {
    $.ajax({
       type: "GET",
       url: baseURL+'dashboard/diff',
       dataType: "json",
       success: function(data)
       {
         renderInitialUi(data);
       }
    });
  };
  getDashboardData();
});
