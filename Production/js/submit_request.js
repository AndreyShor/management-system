const btn = document.getElementById('prof-type-button');
const sb = document.getElementById('prof-type-selector');


btn.onclick = (event) => {
    event.preventDefault();
    // show the selected index
    const value = sb.value;
    const text = sb.options[sb.selectedIndex].text;

    console.log(value);

    if (value == "Visitors") {
        document.location.href = 'https://csee.essex.ac.uk/OSS/view/submit_request_visitor.php';
    }
    else if (value == "phd") {
        document.location.href = 'https://csee.essex.ac.uk/OSS/view/submit_request_phd.php';
    }
    else if (value == "fix") {
        document.location.href = 'https://csee.essex.ac.uk/OSS/view/submit_request_acad.php';
    }
    else if (value == "pro") {
        document.location.href = 'https://csee.essex.ac.uk/OSS/view/submit_request_prof.php';
    }
    else if (value == "ktp") {
        document.location.href = 'https://csee.essex.ac.uk/OSS/view/submit_request_ktp.php';
    }
};



function checkboxClick() {
    // Get the checkbox

    const checkboxWFH = document.getElementById('checkboxWFH');
    const WFHFieldList = document.getElementById('WFHFieldList');

    // If the checkbox is checked, display the output text
    if (checkboxWFH.checked == true){
        WFHFieldList.style.display = "flex";
    } else {
        WFHFieldList.style.display = "none";
    }
    
  }