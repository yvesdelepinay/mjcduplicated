    /*
 * Npm import
 */
import axios from 'axios';
import moment from 'moment';
import 'moment/locale/fr';

/*
 * Local import
 */
import { CHANGE_DATE, SWITCH_PRESENCE, CHANGE_STATE_NOTIFICATION, setActivities, addActivities, setUser, setNextDays, setNotifications } from './reducer';
/*
 * Types
 */
const LOAD_ACTIVITIES = 'LOAD_ACTIVITIES';

/*
 * Code
 */
const createMiddleware = store => next => (action) => {
  let cheminComplet = document.location.href;
  if (cheminComplet.substr(cheminComplet.length - 1, 1) !== '/') {
    cheminComplet += '/';
  }
  switch (action.type) {
    case LOAD_ACTIVITIES:
      {
        // On test le cheminComplet
        console.info(document.location.href);
        // On fait une requête ajax pour récupérer les infos de l'utilisateur +
        // On fait une requête ajax pour récupérer les activités lié à la date et à (l'utilisateur)
        const newDate = moment().format().split('T');
        let path = `${cheminComplet}planning/${newDate[0]}`;
        const params = new URLSearchParams();
        params.append('date', action.currentDate.format());
        axios.post(path, params)
        .then((response) => {
          console.info(response);
          // Je dispatche mon action pour enregistrer ces nouvelles données dans mon
          //  state activités + un dispatch pour enregistrer infos utilisateur
          store.dispatch(setActivities(JSON.parse(response.data.activities)));
          store.dispatch(setUser(response.data.user));
        })
        .catch((error) => {
          console.log(error);
        });
        // Requête ajax pour récupérer les prochaines journées actives pour l'utilisateur
        path = `${cheminComplet}next`;
        axios.post(path, params)
        .then((response) => {
          console.info(response);
          store.dispatch(setNextDays(response.data.nextDayActivities));
        })
        .catch((error) => {
          console.log(error);
        });
        // Requête ajax pour récupérer les notifications pour l'utilisateur
        path = `${cheminComplet}notifications`;
        axios.post(path, params)
        .then((response) => {
          console.info(response);
          store.dispatch(setNotifications(response.data.notifications));
          // On va ensuite récupérer les infos des activités notifiées
          response.data.notifications.map((notif) => {
            path = `${cheminComplet}notification/infos/${notif.activity_type}/${notif.activity_id}`;
            axios.post(path, params)
            .then((response) => {
              console.log("infos notif");
              console.info(response);
              store.dispatch(addActivities(response.data.activities));
            })
            .catch((error) => {
              console.log(error);
            });
          })
        })
        .catch((error) => {
          console.log(error);
        });

        break;
      }
    case CHANGE_DATE:
      {
        const newDate = action.date.format().split('T');
        let CheminComplet = document.location.href;
        if (CheminComplet.substr(CheminComplet.length - 1, 1) !== '/') {
          CheminComplet += '/';
        }
        CheminComplet += `planning/${newDate[0]}`;
        const params = new URLSearchParams();
        params.append('date', action.date.format());
        axios.post(CheminComplet, params)
        .then((response) => {
          console.log(response);
          // Dispatch pour enregistré les nouvelles données des activités
          // de la date selectionnée dans le state
          store.dispatch(setActivities(response.data.activities));
        })
        .catch((error) => {
          console.log(error);
        });
        break;
      }
    case SWITCH_PRESENCE:
      {
        let CheminComplet = document.location.href;
        let path = '';
        if (CheminComplet.substr(CheminComplet.length - 1, 1) !== '/') {
          CheminComplet += '/';
        }
        if (CheminComplet.substr(CheminComplet.length - 5, 5) === '.php/') {
          path = CheminComplet + `lesson/${action.id}/presence/edit`;
        }
        else {
          path = CheminComplet + `../../lesson/${action.id}/presence/edit`;
        }
        const params = new URLSearchParams();
        params.append('id_activity', action.id);
        params.append('type_user', action.userType);
        params.append('presence', !action.presence);
        axios.post(path, params)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
        break;
      }
    case CHANGE_STATE_NOTIFICATION:
      {
        console.error(action);
        let path = document.location.href;
        let newPath = '';
        const pathtab = path.split('/');
        console.info(pathtab);
        for (let iter = 0; iter < pathtab.length - 2; iter += 1) {
          newPath += `${pathtab[iter]}/`;
          console.error(newPath);
        }
        console.info(newPath);
        newPath += `reading_notification/is_read/${action.idNotification}`;
        axios.post(newPath)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
        break;
      }
    default:
  }

  next(action);
};


/*
 * Action creator
 */
export const loadActivities = currentDate => ({
  type: LOAD_ACTIVITIES,
  currentDate,
});

/*
 * Export default
 */
export default createMiddleware;
