<div class="element__hangar" <% if $IndexTitle %> id="$IndexTitle" <% end_if %>>
  <div class="hangar-inner">
    <div class="hangar-textcontainer">
      <% include DividerHorizontal %>
      <h2 class="hangar-title">$Title</h2>
      $HangarContent
    </div>
    <% with $HangarForm %>
      <div class="hangarvisitform">
        <div class="hangarvisitform-success"></div>
        <div class="hangarvisitform-inner">

          <form action="$Up.Link" method="post">

            <fieldset class="hangarvisitform-form">
              <div class="hangarvisitform-fieldunit">
                <div class="hangarvisitform-label">Name</div>
                $Fields.dataFieldByName('Name')
              </div>
              <div class="hangarvisitform-fieldunit">
                <div class="hangarvisitform-label">Email</div>
                $Fields.dataFieldByName('Email')
              </div>
              <div class="hangarvisitform-fieldunit">
                <div class="hangarvisitform-label">Organisation</div>
                $Fields.dataFieldByName('Organisation')
              </div>
              <div class="hangarvisitform-fieldunit">
                <div class="hangarvisitform-label">Date</div>
                $Fields.dataFieldbyName('Date')
              </div>
              $Fields.dataFieldByName('SecurityID')
              $Fields.dataFieldByName('Captcha')
              <button type="submit" class="hangarvisitform-submit">Book now</button>
            </fieldset>
          </form>
        </div>
      </div>
    <% end_with %>
  </div>
</div>
