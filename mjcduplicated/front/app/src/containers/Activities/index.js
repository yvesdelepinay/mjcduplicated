/*
 * Npm import
 */
import { connect } from 'react-redux';
// import { bindActionCreators } from 'redux';

/*
 * Local import
 */
import Activities from 'src/components/Activities';
import { } from 'src/store/reducer';

/*
 * Code
 */
const mapStateToProps = state => ({
  activities: state.activities,
});

const mapDispatchToProps = null;

/*
 * Component
 */
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ActivityContainer = createContainer(Activities);


/*
 * Export default
 */
export default ActivityContainer;
