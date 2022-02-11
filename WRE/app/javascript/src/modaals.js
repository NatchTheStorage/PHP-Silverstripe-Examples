$(".ModalBookAppraisal").modaal({
    content_source: '#ModalBookAppraisal',
    custom_class: 'modal__appraisal',
});

// $(".ModalTeam").modaal({
//     content_source: '#ModalTeam',
//     custom_class: 'modal__teamwrapper',
//     before_open: UpdateModal
// });

$(".ModalBookAppraisalSuccess").modaal({
    content_source: '#ModalBookAppraisalSuccess',
    custom_class: 'modal__successwrapper',
});

$(".ModalGeneralSuccess").modaal({
    content_source: '#ModalGeneralSuccess',
    custom_class: 'modal__successwrapper',
});

//======================================================================================================================
const appraisalAlertMessage = document.getElementById('checkboxmessage');
const submitAppraisalButton = document.getElementById('Form_AppraisalForm_action_SubmitAppraisal');
if (submitAppraisalButton) {
    submitAppraisalButton.addEventListener('click', function () {
        if (radioOption1.checked === false && radioOption2.checked === false) {
            appraisalAlertMessage.innerHTML = 'Please select an option!';
        }
        else {
            appraisalAlertMessage.innerHTML = '';
        }

    })
}

//======================================================================================================================

function UpdateModal(event) {
    const targetElement = event.target.parentNode;
    if (targetElement.dataset.aboutname) {
        document.getElementById('modalteam-fullname').innerHTML=targetElement.dataset.aboutname;
    }
    if (targetElement.dataset.aboutsrc) {
        document.getElementById('modalteam-staffimg').src=targetElement.dataset.aboutsrc;
    }
    if (targetElement.dataset.aboutposition) {
        document.getElementById('modalteam-position').innerHTML=targetElement.dataset.aboutposition;
    }
    if (targetElement.dataset.aboutoffice) {
        document.getElementById('modalteam-office').innerHTML=targetElement.dataset.aboutoffice;
    }
    if (targetElement.dataset.aboutmobile) {
        document.getElementById('modalteam-mobile').innerHTML=targetElement.dataset.aboutmobile;
        document.getElementById('modalteam-mobile').setAttribute('href', 'tel:'
            + targetElement.dataset.aboutmobile)
    }
    if (targetElement.dataset.aboutemail) {
        document.getElementById('modalteam-email').innerHTML=targetElement.dataset.aboutemail;
        document.getElementById('modalteam-email').setAttribute('href', 'mailto:'
            + targetElement.dataset.aboutemail + '?Subject=Website Enquiry"')
    }
    if (!targetElement.dataset.aboutdescription) {
        document.getElementById('modalteam-description').innerHTML='';
    }
    else {
        document.getElementById('modalteam-description').innerHTML=targetElement.dataset.aboutdescription;
    }

    if (targetElement.dataset.aboutrole) {
        if (targetElement.dataset.aboutrole!=='frontoffice' && targetElement.dataset.aboutrole!=='management') {
            document.getElementById('modalteam-myListings').style.display = 'block';
            document.getElementById('modalteam-myListings').setAttribute('href', targetElement.dataset.aboutmyListings);

            document.getElementById('modalteam-myListingsmobile').style.display = 'block';
            document.getElementById('modalteam-myListingsmobile').setAttribute('href', targetElement.dataset.aboutmyListings);
            if (targetElement.dataset.aboutrole!=='propertyManagement') {
                document.getElementById('modalteam-mySales').style.display = 'block';
                document.getElementById('modalteam-mySales').setAttribute('href', targetElement.dataset.aboutmySales);
                document.getElementById('modalteam-mySalesmobile').style.display = 'block';
                document.getElementById('modalteam-mySalesmobile').setAttribute('href', targetElement.dataset.aboutmySales);
            }
            else {
                document.getElementById('modalteam-mySales').style.display = 'none';
                document.getElementById('modalteam-mySalesmobile').style.display = 'none';
            }
        }
        else {
            document.getElementById('modalteam-mySales').style.display = 'none';
            document.getElementById('modalteam-myListings').style.display = 'none';
            document.getElementById('modalteam-mySalesmobile').style.display = 'none';
            document.getElementById('modalteam-myListingsmobile').style.display = 'none';
        }
    }

    document.getElementById('modalteam-myListings').href=targetElement.dataset.aboutmylistings;
    document.getElementById('modalteam-mySales').href=targetElement.dataset.aboutmysales;
    document.getElementById('modalteam-myListingsmobile').href=targetElement.dataset.aboutmylistings;
    document.getElementById('modalteam-mySalesmobile').href=targetElement.dataset.aboutmysales;
}
