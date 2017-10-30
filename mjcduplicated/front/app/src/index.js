/*
 * Npm import
 */
import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, browserHistory } from 'react-router-dom';
import moment from 'moment';

/*
 * Local import
 */
import App from 'src/components/App';
import store from 'src/store';
import { loadActivities } from 'src/store/middleware';

/*
 * Code
 */
document.addEventListener('DOMContentLoaded', () => {
  const provider = (
    <Provider store={store}>
      <Router history={browserHistory}>
        <App />
      </Router>
    </Provider>
  );
  render(provider, document.getElementById('root'));
  store.dispatch(loadActivities(moment()));
});
