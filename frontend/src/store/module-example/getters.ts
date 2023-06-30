import { GetterTree } from 'vuex';
import { RootState } from '../types';
import { ExampleStateInterface } from './state';

const getters: GetterTree<ExampleStateInterface, RootState> = {
  someAction(/* context */) {
    // your code
  },
};

export default getters;
