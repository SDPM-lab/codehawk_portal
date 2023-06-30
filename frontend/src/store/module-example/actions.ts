import { ActionTree } from 'vuex';
import { RootState } from '../types';
import { ExampleStateInterface } from './state';

const actions: ActionTree<ExampleStateInterface, RootState> = {
  someAction(/* context */) {
    // your code
  },
};

export default actions;
