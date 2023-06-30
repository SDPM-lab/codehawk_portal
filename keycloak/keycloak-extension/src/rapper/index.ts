/* md5: ab403e23d8d2bf8840cef53e0b54aa15 */
/* Rap仓库id: 18 */
/* Rapper版本: 1.2.0 */
/* eslint-disable */
/* tslint:disable */
// @ts-nocheck

/**
 * 本文件由 Rapper 同步 Rap 平台接口，自动生成，请勿修改
 * Rap仓库 地址: https://rap2.sdpmlab.org/repository/editor?id=18
 */

import {createFetch, IModels} from './request'
import * as commonLib from 'rap/runtime/commonLib'

const {defaultFetch} = commonLib
let fetch = createFetch({}, {fetchType: commonLib.FetchType.BASE})

export const overrideFetch = (fetchConfig: commonLib.RequesterOption) => {
  fetch = createFetch(fetchConfig, {fetchType: commonLib.FetchType.AUTO})
}
export {fetch, createFetch, defaultFetch}
export type Models = IModels
