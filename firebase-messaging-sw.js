
        self.onnotificationclick = (event) => {
            if(event.notification.data.FCM_MSG.data.click_action){
               event.notification.close();
               event.waitUntil(clients.matchAll({
                    type: 'window'
               }).then((clientList) => {
                  for (const client of clientList) {
                      if (client.url === '/' && 'focus' in client)
                          return client.focus();
                      }
                  if (clients.openWindow)
                      return clients.openWindow(event.notification.data.FCM_MSG.data.click_action);
                  }));
            }
        };
        importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
               importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

        const firebaseConfig = {
        apiKey: "AIzaSyDEyt3_KR5-KPhDUNIXVo_qObJxti0Oo10",
        authDomain: "finounce-66b76.firebaseapp.com",
        projectId: "finounce-66b76",
        storageBucket: "finounce-66b76.appspot.com",
        messagingSenderId: "334274331871",
        appId: "1:334274331871:web:b6128b251e56fc8da1d56a",
        measurementId: "334274331871"
        };

       const app = firebase.initializeApp(firebaseConfig);
       const messaging = firebase.messaging();

       messaging.setBackgroundMessageHandler(function (payload) {
       if (payload.notification.background && payload.notification.background == 1) {
          const title = payload.notification.title;
          const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
          };
          return self.registration.showNotification(
            title,
            options,
          );
       }
        });