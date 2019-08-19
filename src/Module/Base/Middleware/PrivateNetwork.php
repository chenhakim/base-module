<?php
namespace Module\Base\Middleware;

use Closure;
use Cache, Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * 限制私有网络（内网）
 *
 * @author lin
 */
class PrivateNetwork
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 取得请求IP。
        $ip = getClientIp();

        // 允许关闭IP限制时访问
        // 允许调试模式下本地访问。
        if (! env('IP_LIMIT', true) || config('app.debug') && isInternalNetwork($ip)) {
            return $next($request);
        }

        // 取得IP白名单。
        $config = config('module-base.ip_white_list');
        $arrWhiteList = Cache::store($config['store'])->get($config['key'], []);

        // 检查IP是否在白名单中。
        if (! in_array($ip, $arrWhiteList)) {
            Log::info('Access denied', [
                'ip' => join(',', $request->getClientIps()) . ',' . $ip,
                'path' => $request->path(),
                'input' => $request->input(),
                'user-agent' => $request->header('user-agent')
            ]);
            throw new AccessDeniedHttpException('Access denied.');
//            return response('Access denied.', 403);
        }

        return $next($request);
    }

}
