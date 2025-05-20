function createWindowNotification(elem, textContent, error = false) {

  let newNotification = document.createElement('div');
  newNotification.classList.add('window-box');

  let closeButton = document.createElement('span');
  closeButton.classList.add("close-btn");
  closeButton.innerHTML = "&times;";
  closeButton.style.color = 'black';
  closeButton.onclick = () => { newNotification.remove() };

  newNotification.appendChild(closeButton);

  let text = document.createElement('div');
  if (error)
    text.style.color = "red";
  else
    text.style.color = "green";
  text.classList.add('floating-content');
  text.innerText = textContent;

  newNotification.appendChild(text);
  setTimeout(() => {
    newNotification.classList.add("vanishing");
    setTimeout(() => {
      //newNotification.remove();
    }, 500);
  }, 1500);

  elem.appendChild(newNotification);
}
