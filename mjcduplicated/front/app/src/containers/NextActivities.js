/*
 * Npm import
 */
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
/*
 * Local import
 */
import NextActivities from 'src/components/NextActivities';
import { changeDate } from 'src/store/reducer';

/*
 * Code
 */
const mapStateToProps = state => ({
  days: state.nextDayActivities,
  displayNotifications: state.displayNotifications,
});

const mapDispatchToProps = dispatch => ({
  actions: bindActionCreators({ changeDate }, dispatch),
});

/*
 * Component
 */
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const NextActivitiesContainer = createContainer(NextActivities);


/*
 * Export default
 */
export default NextActivitiesContainer;
