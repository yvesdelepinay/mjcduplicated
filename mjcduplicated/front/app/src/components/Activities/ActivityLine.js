/*
 * Line of one activity of the notebook.
 */

/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

/*
 * Local import
 */
import Presence from 'src/containers/Presence';


/*
 * Code
 */
const ActivityLine = ({ startHour, finishHour, activity_id: id,
  speciality, presenceStudent, presenceTeacher, student, teacher, user }) => {
  // Get the state of the activity in depend of the presence of the 2 users (teacher / student).
  const stateActivity = (presenceTeacher && presenceStudent);
  // Get the good name of the second user who doing the activity with the current user,
  // ex: If it's the student then we pick the teacher name.
  const interlocuteur = user.user_role === 'ROLE_STUDENT' ? teacher : student;
  return (
    <div className="activity">
      <div className="activity-infos">
        {startHour} - {finishHour} {speciality} avec {interlocuteur}
        <Link
          className="activity-link"
          to={`/ProjectMJC/projetMJC/web/app.php/activity/${id}`}
        >
          <button className="show-activity-button">Voir</button>
        </Link>
      </div>
      <Presence
        presenceTeacher={presenceTeacher}
        presenceStudent={presenceStudent}
        stateActivity={stateActivity}
        id={id}
      />
    </div>
  );
};

ActivityLine.propTypes = {
  startHour: PropTypes.string.isRequired,
  finishHour: PropTypes.string.isRequired,
  activity_id: PropTypes.number.isRequired,
  speciality: PropTypes.string.isRequired,
  presenceStudent: PropTypes.bool.isRequired,
  presenceTeacher: PropTypes.bool.isRequired,
  student: PropTypes.string.isRequired,
  teacher: PropTypes.string.isRequired,
  user: PropTypes.object.isRequired,
};

/*
 * Export default
 */
export default ActivityLine;
