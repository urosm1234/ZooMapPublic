function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const menuButton = document.getElementById('menu-button');
    const menuArrowImg = document.getElementById('menu-arrow-img');
    //menuButton.onclick= "";

    if(!menuButton.classList.contains('fadeOut'))
    {
        menuButton.classList.add('fadeOut');
        sidebar.classList.toggle('expanded');
        setTimeout(function(){
            if(menuArrowImg.src.includes("left-arrow.png"))
            {
                menuArrowImg.src = PATH + "images/right-arrow.png";
            }
            else
            {
                menuArrowImg.src = PATH + "images/left-arrow.png";
            }
            menuButton.classList.remove('fadeOut');
            menuButton.classList.add('fadeIn');
            setTimeout(function(){
                menuButton.classList.remove('fadeIn');
            },250);
        },250);
    }

}
