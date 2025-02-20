var initialData =
{
	ajaxLink: "confmanager/credentials/datatables/",
	tableSelector: '#credosDataTable',
	item: 'confQueries',
	//deleteItems: tgui_userApi.delete,
	//exportCsv: tgui_userApi.csvDownload || function(){return false;},
  columns:
	{
		id: {title: "ID", data : "id", orderable: true, visible: false,},
		name: {title: "Name", data : "name", visible: true, orderable: true},
		username: {title: "Username", data : "username", visible: true, orderable: true},
		ref_d: {title: "Ref. Devices", data : "ref_d", visible: false, orderable: false},
		ref_q: {title: "Ref. Queries", data : "ref_q", visible: false, orderable: false},
		buttons: {title: "Action", data : "buttons", visible: true, orderable: false},
	},
  column:
	{
		select: true,
		preview: false
	},
  sort:
	{
		column: 2,
		order: 'asc'
	},
};

var dataTable = {
	init: function() {
		this.settings.columnsFilter();
		//this.settings.preview();
		this.settings.columnDefs = [],
		//console.log(this.settings);
		this.table = $(initialData.tableSelector).DataTable(this.settings);
	},
	table: {},
	settings: new tgui_datatables(initialData),
};

//$.fn.dataTable.ext.errMode = 'throw';
