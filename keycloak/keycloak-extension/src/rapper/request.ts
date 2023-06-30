/* md5: b91b26a5ad8b740b95e4421eeb2547c3 */
/* Rap仓库id: 18 */
/* Rapper版本: 1.2.0 */
/* eslint-disable */
/* tslint:disable */
// @ts-nocheck

/**
 * 本文件由 Rapper 同步 Rap 平台接口，自动生成，请勿修改
 * Rap仓库 地址: https://rap2.sdpmlab.org/repository/editor?id=18
 */

import * as commonLib from 'rap/runtime/commonLib'

export interface IModels {
  /**
   * 接口名：Read
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=147
   */
  'GET/api/v1/user/:username': {
    Req: {
      /**
       * 使用者通行權杖
       */
      'X-Access-Token'?: string
      username?: string
    }
    Res: {
      status_code: string
      data: {
        first_name: string
        last_name: string
        email: string
        username: string
      }
    }
  }

  /**
   * 接口名：Logout
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=150
   */
  'DELETE/api/v1/user/:accessToken': {
    Req: {
      'X-Refresh-Token'?: string
      accessToken?: string
    }
    Res: {
      status_code: string
      logout_url: string
    }
  }

  /**
   * 接口名：Refresh
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=149
   */
  'PUT/api/v1/user/refresh': {
    Req: {
      'X-Access-Tonken'?: string
      'X-Refresh-Tonken'?: string
    }
    Res: {
      access_token: string
      status_code: string
    }
  }

  /**
   * 接口名：Login
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=154
   */
  'GET/api/v1/user/login': {
    Req: {}
    Res: {}
  }

  /**
   * 接口名：getToken
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=145
   */
  'POST/api/v1/user/login': {
    Req: {
      /**
       * OpenID Server 認證後回傳的登入 Code
       */
      'X-Oidc-Code'?: string
    }
    Res: {
      status_code: string
      data: {
        accessToken: string
        refreshToken: string
      }
    }
  }

  /**
   * 接口名：ServicesLogin
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=148
   */
  'POST/api/v1/user/service/login': {
    Req: {
      /**
       * 使用者帳號
       */
      username: string
      password: string
    }
    Res: {
      status_code: string
    }
  }

  /**
   * 接口名：ServiceSignup
   * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=151
   */
  'POST/api/v1/user/service': {
    Req: {
      email: string
      password: string
      first_name: string
      last_name: string
      confirm_password: string
    }
    Res: {
      status_code: string
    }
  }
}

type ResSelector<T> = T

export interface IResponseTypes {
  'GET/api/v1/user/:username': ResSelector<IModels['GET/api/v1/user/:username']['Res']>
  'DELETE/api/v1/user/:accessToken': ResSelector<IModels['DELETE/api/v1/user/:accessToken']['Res']>
  'PUT/api/v1/user/refresh': ResSelector<IModels['PUT/api/v1/user/refresh']['Res']>
  'GET/api/v1/user/login': ResSelector<IModels['GET/api/v1/user/login']['Res']>
  'POST/api/v1/user/login': ResSelector<IModels['POST/api/v1/user/login']['Res']>
  'POST/api/v1/user/service/login': ResSelector<IModels['POST/api/v1/user/service/login']['Res']>
  'POST/api/v1/user/service': ResSelector<IModels['POST/api/v1/user/service']['Res']>
}

export function createFetch(fetchConfig: commonLib.RequesterOption, extraConfig?: {fetchType?: commonLib.FetchType}) {
  if (!extraConfig || !extraConfig.fetchType) {
    console.warn(
      'Rapper Warning: createFetch API will be deprecated, if you want to customize fetch, please use overrideFetch instead, since new API guarantees better type consistency during frontend lifespan. See detail https://www.yuque.com/rap/rapper/overridefetch'
    )
  }
  const rapperFetch = commonLib.getRapperRequest(fetchConfig)

  return {
    /**
     * 接口名：Read
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=147
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'GET/api/v1/user/:username': (req?: IModels['GET/api/v1/user/:username']['Req'], extra?: commonLib.IExtra) => {
      return rapperFetch({
        url: '/api/v1/user/:username',
        method: 'GET',
        params: req,
        extra,
      }) as Promise<IResponseTypes['GET/api/v1/user/:username']>
    },

    /**
     * 接口名：Logout
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=150
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'DELETE/api/v1/user/:accessToken': (
      req?: IModels['DELETE/api/v1/user/:accessToken']['Req'],
      extra?: commonLib.IExtra
    ) => {
      return rapperFetch({
        url: '/api/v1/user/:accessToken',
        method: 'DELETE',
        params: req,
        extra,
      }) as Promise<IResponseTypes['DELETE/api/v1/user/:accessToken']>
    },

    /**
     * 接口名：Refresh
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=149
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'PUT/api/v1/user/refresh': (req?: IModels['PUT/api/v1/user/refresh']['Req'], extra?: commonLib.IExtra) => {
      return rapperFetch({
        url: '/api/v1/user/refresh',
        method: 'PUT',
        params: req,
        extra,
      }) as Promise<IResponseTypes['PUT/api/v1/user/refresh']>
    },

    /**
     * 接口名：Login
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=154
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'GET/api/v1/user/login': (req?: IModels['GET/api/v1/user/login']['Req'], extra?: commonLib.IExtra) => {
      return rapperFetch({
        url: '/api/v1/user/login',
        method: 'GET',
        params: req,
        extra,
      }) as Promise<IResponseTypes['GET/api/v1/user/login']>
    },

    /**
     * 接口名：getToken
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=145
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'POST/api/v1/user/login': (req?: IModels['POST/api/v1/user/login']['Req'], extra?: commonLib.IExtra) => {
      return rapperFetch({
        url: '/api/v1/user/login',
        method: 'POST',
        params: req,
        extra,
      }) as Promise<IResponseTypes['POST/api/v1/user/login']>
    },

    /**
     * 接口名：ServicesLogin
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=148
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'POST/api/v1/user/service/login': (
      req?: IModels['POST/api/v1/user/service/login']['Req'],
      extra?: commonLib.IExtra
    ) => {
      return rapperFetch({
        url: '/api/v1/user/service/login',
        method: 'POST',
        params: req,
        extra,
      }) as Promise<IResponseTypes['POST/api/v1/user/service/login']>
    },

    /**
     * 接口名：ServiceSignup
     * Rap 地址: https://rap2.sdpmlab.org/repository/editor?id=18&mod=50&itf=151
     * @param req 请求参数
     * @param extra 请求配置项
     */
    'POST/api/v1/user/service': (req?: IModels['POST/api/v1/user/service']['Req'], extra?: commonLib.IExtra) => {
      return rapperFetch({
        url: '/api/v1/user/service',
        method: 'POST',
        params: req,
        extra,
      }) as Promise<IResponseTypes['POST/api/v1/user/service']>
    },
  }
}
