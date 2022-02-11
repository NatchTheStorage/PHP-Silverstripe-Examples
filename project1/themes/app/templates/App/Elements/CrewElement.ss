<div id="$IndexTitle" class="aboutus__crew">
  <% include DividerHorizontal %>
  <h2 class="aboutus__crew-title">
    <% if $DisplayTitle %>
      $DisplayTitle
    <% else %>
      Crew
    <% end_if %>
  </h2>

  <% if $GetPilots %>
    <div class="aboutus__crew-content">
      <h2 class="aboutus__crew-contenttitle">$PilotsTitle</h2>
      <div class="aboutus__crew-contentdescription">$PilotsDescription</div>
      <div class="aboutus__crewlist">
        <% loop $GetPilots %>
          <% include CardCrew %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>

  <% if $GetCrewmans %>
    <div class="aboutus__crew-content">
      <h2 class="aboutus__crew-contenttitle">$CrewmanTitle</h2>
      <div class="aboutus__crew-contentdescription">$CrewmanDescription</div>
      <div class="aboutus__crewlist">
        <% loop $GetCrewmans %>
          <% include CardCrew %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>

  <% if $GetParamedics %>
    <div class="aboutus__crew-content">
      <h2 class="aboutus__crew-contenttitle">$ParamedicsTitle</h2>
      <div class="aboutus__crew-contentdescription">$ParamedicsDescription</div>
      <div class="aboutus__crewlist">
        <% loop $GetParamedics %>
          <% include CardCrew %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>

  <% if $GetOperations %>
    <div class="aboutus__crew-content">
      <h2 class="aboutus__crew-contenttitle">$OperationsTitle</h2>
      <div class="aboutus__crew-contentdescription">$OperationsDescription</div>
      <div class="aboutus__crewlist">
        <% loop $GetOperations %>
          <% include CardCrew %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>

  <% if $GetBoards %>
    <div class="aboutus__crew-content">
      <h2 class="aboutus__crew-contenttitle">$BoardsTitle</h2>
      <div class="aboutus__crew-contentdescription">$BoardsDescription</div>
      <div class="aboutus__crewlist">
        <% loop $GetBoards %>
          <% include CardCrew %>
        <% end_loop %>
      </div>
    </div>
  <% end_if %>
</div>