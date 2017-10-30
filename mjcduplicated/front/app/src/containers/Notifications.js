/*
 * Npm import
 */
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
/*
 * Local import
 */
import Notifications from 'src/components/Notifications';
import { displayNotifications, changeNotificationState } from 'src/store/reducer';
/*
 * Code
 */
const mapStateToProps = state => ({
  notifications: state.notifications,
  displayNotifications: state.displayNotifications,
});

const mapDispatchToProps = dispatch => ({
  actions: bindActionCreators({ displayNotifications, changeNotificationState }, dispatch),
});

/*
 * Component
 */
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const NotificationsLineContainer = createContainer(Notifications);


/*
 * Export default
 */
export default NotificationsLineContainer;
