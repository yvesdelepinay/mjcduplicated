/*
 * Npm Import
 */
import moment from 'moment';
import 'moment/locale/fr';
/*
 * Local import
 */
// import datas from 'src/datas';
/*
 * Types
 */
export const CHANGE_DATE = 'CHANGE_DATE';
export const UP_DAY = 'UP_DAY';
export const DOWN_DAY = 'DOWN_DAY';
const SET_ACTIVITIES = 'SET_ACTIVITIES';
const ADD_ACTIVITIES = 'ADD_ACTIVITIES';
const SET_USER = 'SET_USER';
const SET_NEXTDAYS = 'SET_NEXTDAYS';
const SET_NOTIFICATIONS = 'SET_NOTIFICATIONS';
export const SWITCH_PRESENCE = 'SWITCH_PRESENCE';
const INPUT_OBSERVATION_CHANGE = 'INPUT_OBSERVATION_CHANGE';
export const RESET_OBSERVATION = 'RESET_OBSERVATION';
const DISPLAY_NOTIFICATIONS = 'DISPLAY_NOTIFICATIONS';
export const CHANGE_STATE_NOTIFICATION = 'CHANGE_STATE_NOTIFICATIONS';

/*
 * initialState
 */
export const initialState = {
  currentDate: moment(),
  user: {},
  activities: [],
  activitiesNotif: [],
  notifications: [],
  nextDayActivities: [],
  inputObservation: '',
  displayNotifications: false,
};


/*
 * Reducer
 */
export default (state = initialState, action = {}) => {
  switch (action.type) {
    case CHANGE_DATE:
      {
        return {
          ...state,
          currentDate: action.date,
        };
      }
    case SET_ACTIVITIES:
      {
        return {
          ...state,
          activities: action.activities,
        };
      }
    case ADD_ACTIVITIES:
      {
        return {
          ...state,
          activitiesNotif: state.activitiesNotif.concat(action.activities),
        };
      }
    case SET_USER:
      {
        return {
          ...state,
          user: action.user,
        };
      }
    case SET_NEXTDAYS:
      {
        return {
          ...state,
          nextDayActivities: action.nextDayActivities,
        };
      }
    case SET_NOTIFICATIONS:
      {
        return {
          ...state,
          notifications: action.notifications,
        };
      }
    case SWITCH_PRESENCE:
      {
        let { id } = action;
        id = parseInt(id, 10);
        const activities = [...state.activities];
        activities.forEach((activity) => {
          if (activity.activity_id === id) {
            if (action.userType === 'ROLE_TEACHER') {
              activity.presenceTeacher = !activity.presenceTeacher;
            }
            else {
              activity.presenceStudent = !activity.presenceStudent;
            }
          }
        });
        return {
          ...state,
          activities,
        };
      }
    case INPUT_OBSERVATION_CHANGE:
      {
        let { id } = action;
        const { input } = action;
        id = parseInt(id, 10);
        const activities = [...state.activities];
        activities.forEach((activity) => {
          if (activity.activity_id === id) {
            activity.appreciation = input;
          }
        });
        return {
          ...state,
          activities,
        };
      }
    case RESET_OBSERVATION:
      {
        let { id } = action;
        id = parseInt(id, 10);
        const activities = [...state.activities];
        activities.forEach((activity) => {
          if (activity.activity_id === id) {
            console.info('action : Axios récupérer lobservation en bdd pour cette activité');
            // activity.appreciation = activity.observation;
          }
        });
        return {
          ...state,
          activities,
        };
      }
    case DISPLAY_NOTIFICATIONS:
      {
        const display = !state.displayNotifications;
        return {
          ...state,
          displayNotifications: display,
        };
      }
    case CHANGE_STATE_NOTIFICATION:
      {
        const { idActivity, idNotification, date } = action;
        const notifications = [...state.notifications];
        notifications.forEach((notif) => {
          if (notif.notification_id === idNotification) {
            notif.is_read = true;
          }
        });
        return {
          ...state,
          notifications,
        };
      }
    default:
      return state;
  }
};

/*
 * Action creators
 */
export const changeDate = date => ({
  type: CHANGE_DATE,
  date,
});
export const upDay = () => ({
  type: UP_DAY,
});
export const downDay = () => ({
  type: DOWN_DAY,
});
export const switchPresenceTeacher = (id, presenceTeacher) => ({
  type: SWITCH_PRESENCE,
  userType: 'ROLE_TEACHER',
  presence: presenceTeacher,
  id,
});
export const switchPresenceStudent = (id, presenceStudent) => ({
  type: SWITCH_PRESENCE,
  userType: 'ROLE_STUDENT',
  presence: presenceStudent,
  id,
});
export const setActivities = activities => ({
  type: SET_ACTIVITIES,
  activities,
});
export const addActivities = activities => ({
  type: ADD_ACTIVITIES,
  activities,
});
export const setUser = user => ({
  type: SET_USER,
  user,
});
export const setNextDays = nextDayActivities => ({
  type: SET_NEXTDAYS,
  nextDayActivities,
});
export const setNotifications = notifications => ({
  type: SET_NOTIFICATIONS,
  notifications,
});
export const changeInputObservation = (input, id) => ({
  type: INPUT_OBSERVATION_CHANGE,
  input,
  id,
});
export const resetObservation = id => ({
  type: RESET_OBSERVATION,
  id,
});
export const displayNotifications = () => ({
  type: DISPLAY_NOTIFICATIONS,
});
export const changeNotificationState = (idActivity, idNotification, date) => ({
  type: CHANGE_STATE_NOTIFICATION,
  idActivity,
  idNotification,
  date,
});


/*
 * Action Selectors
 */
export const selectActivity = (state, props) => {
  const id = parseInt(props, 10);
  let activitySelected = state.activities.filter(activity => (
    activity.activity_id === id
  ));
  if (activitySelected.length) {
    return activitySelected[0];
  }
  activitySelected = state.activitiesNotif.filter(activity => (
    activity.activity_id === id
  ));
  if (activitySelected.length) {
    return activitySelected[0];
  }
  return null;
};
