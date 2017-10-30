/*
 * Show presence current user + button for switch
 */

/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
/*
 * Local import
 */

/*
 * Code
 */
const Presence = ({ presenceTeacher, presenceStudent, actions, stateActivity, user }) => {
  // Check type user and get his presence state
  const presenceType = user.user_role === 'ROLE_STUDENT' ? presenceStudent : presenceTeacher;
  // Check type user and get the good action function
  const switchPresenceUser = user.user_role === 'ROLE_STUDENT' ? actions.switchPresenceStudent : actions.switchPresenceTeacher;
  return (
    <div className="presence">
      <div onClick={switchPresenceUser} className="one presence-button">
        <div
          className={classNames(
          'button-wrap',
          { 'button-active': presenceType },
          { present: stateActivity },
          )}
        >
          <div className="button-bg">
            <div className="button-out">Absent</div>
            <div className="button-in">Présent</div>
            <div className="button-switch" />
          </div>
        </div>
      </div>
      <div className="activity-statut">
        <span
          className={classNames(
          'activity-state',
          { absent: !stateActivity },
          { present: stateActivity },
        )}
        >
          {stateActivity ? <span className="label label-success">Cours</span> : <span className="label label-danger">Annulé</span>}
        </span>
      </div>
    </div>
  );
};

/*
 * propTypes
 */
Presence.propTypes = {
  presenceTeacher: PropTypes.bool.isRequired,
  presenceStudent: PropTypes.bool.isRequired,
  actions: PropTypes.objectOf(PropTypes.func.isRequired).isRequired,
  stateActivity: PropTypes.bool.isRequired,
  user: PropTypes.object.isRequired,
};

/*
 * Export default
 */
export default Presence;
