/*
 * Npm import
 */
import { connect } from 'react-redux';

/*
 * Local import
 */
import Presence from 'src/components/Presence';
import { switchPresenceTeacher, switchPresenceStudent } from 'src/store/reducer';

/*
 * Code
 */
const mapStateToProps = state => ({
  user: state.user,
});

const mapDispatchToProps = (dispatch, props) => ({
  actions: {
    switchPresenceTeacher: () => {
      dispatch(switchPresenceTeacher(props.id, props.presenceTeacher));
    },
    switchPresenceStudent: () => {
      dispatch(switchPresenceStudent(props.id, props.presenceStudent));
    },
  },
});

/*
 * Component
 */
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const PresenceContainer = createContainer(Presence);


/*
 * Export default
 */
export default PresenceContainer;
