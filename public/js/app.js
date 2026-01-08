// filepath: c:\Data Data\Project\TheParanoia\public\js\app.js

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Show your custom install prompt
  showInstallPrompt();
});

function showInstallPrompt() {
  // Buat elemen popup sederhana (atau gunakan library seperti Bootstrap modal)
  const installButton = document.createElement('button');
  installButton.innerText = 'Install';
  installButton.style.position = 'fixed';
  installButton.style.bottom = '20px';
  installButton.style.right = '20px';
  installButton.style.zIndex = '1000';
  installButton.onclick = () => {
    // Hide the prompt
    installButton.remove();
    // Show the install prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('User accepted the install prompt');
      } else {
        console.log('User dismissed the install prompt');
      }
      deferredPrompt = null;
    });
  };
  document.body.appendChild(installButton);
}
