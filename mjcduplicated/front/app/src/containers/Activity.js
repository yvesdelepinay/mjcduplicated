/*
 * Npm import
 */
import { connect } from 'react-redux';
/*
 * Local import
 */
import Activity from 'src/components/Activity';
import { selectActivity, changeInputObservation, resetObservation } from 'src/store/reducer';

/*
 * Code
 */
const mapStateToProps = (state, ownProps) => {
  const activity = selectActivity(state, ownProps.match.params.id);
  return ({
    currentDate: state.currentDate,
    ...activity,
    user: state.user,
    inputObservation: state.inputObservation,
  });
};

const mapDispatchToProps = (dispatch, ownProps) => ({
  actions: {
    changeInputObservation: (input) => {
      dispatch(changeInputObservation(input, ownProps.match.params.id));
    },
    resetObservation: () => {
      dispatch(resetObservation(ownProps.match.params.id));
    },
  },
});

/*
 * Component
 */
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ActivityContainer = createContainer(Activity);


/*
 * Export default
 */
export default ActivityContainer;
