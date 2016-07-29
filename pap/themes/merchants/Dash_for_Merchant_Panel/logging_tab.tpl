<!-- logging_tab -->
<div class="TabDescription">

  <div class="FormFieldset Log">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Log level##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="log_level"}
  </div>

  <div class="FormFieldset Debug">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Debug##</div>
  		<div class="FormFieldsetHeaderDescription">##Debugging can be used for troubleshooting. You can choose actions where the system will use INFORMATION log level with debug messages. Otherwise is used general log level for all actions. You can use these messages to investigate the flow of commands, and to find out what is wrong. This way you can check what are the scripts doing and where exactly they fail. In production it should be turned off, because it generates multiple history records for each transaction and slows down the system.##</div>
  	</div>
    {widget id="aditionalDescription"}
    {widget id="panelDebugSpecialSettings"}
  </div>

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Delete old events##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    <div class="Inliner"><div class="Label">##Delete event records older than##</div></div>
    <div class="FormFieldSmallInline">{widget id="deleteeventdays"}</div><div class="Inliner">##days##</div>
    <div class="Inliner">{widget id="helpAutoDeleteEvents"}</div>
    <div class="clear"></div>
    <div class="Inliner"><div class="Label">##Truncate all events if there are more than ##</div></div>
    <div class="FormFieldSmallInline">{widget id="deleteeventrecords"}</div><div class="Inliner">##records in logs table##</div>
    <div class="Inliner">{widget id="helpAutoDeleteEventsMaxRecordsCount"}</div>
    <div class="clear"></div>
  </div>
  
  {widget id="panelLoginsHistorySettings"}
  
  <div class="pad_left">
  {widget id="SaveButton"}
  </div>
  <div class="clear"></div>
</div>
