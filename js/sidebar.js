function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const menuButton = document.getElementById('menu-button');
    const menuArrowImg = document.getElementById('menu-arrow-img');
    //menuButton.onclick= "";
    console.log(menuArrowImg.src);
    menuButton.classList.add('fadeOut');
    sidebar.classList.toggle('expanded');
    console.log("Started");
    setTimeout(function(){
        if(menuArrowImg.src.includes(PATH+"images/arrow-left.png"))
        {
            menuArrowImg.src = PATH + "images/arrow-right.png";
        }
        else
        {
            menuArrowImg.src = PATH + "images/arrow-left.png";
        }
        console.log("Finished")
        menuButton.classList.add('fadeIn');
        menuButton.classList.remove('fadeOut');
        setTimeout(function(){
            menuButton.classList.remove('fadeIn');
            //menuButton.onclick = toggleSidebar();
        },1000);
    },1000);
}