/*
 * List of all activity.
 */
/*
 * Npm import
 */
import React from 'react';
import PropTypes from 'prop-types';

/*
 * Local import
 */
import ActivityLine from 'src/containers/Activities/ActivityLine';

/*
 * Code
 */
const Activities = ({ activities }) => (
  <div id="activities">
    {activities.length > 0 ?
      activities.map(lesson => (
        <ActivityLine key={lesson.activity_id} {...lesson} />
    ))
    :
      <h1 id="no-activity-title">Aucune Activité</h1>
    }
  </div>
);

Activities.propTypes = {
  activities: PropTypes.arrayOf(PropTypes.object.isRequired).isRequired,
};

/*
 * Export default
 */
export default Activities;
