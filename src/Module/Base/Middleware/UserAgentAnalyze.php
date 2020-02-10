<?php
namespace Module\Base\Middleware;

use Closure;

/**
 * 解析UserAgent公共参数
 *
 */
class UserAgentAnalyze
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
        // 取出UserAgent。
        $user_agent = env('HEADER_USER_AGENT', $request->header('user-agent'));

        // 要并入Request的Header。
        $data = [];

        // 正则取出相关公共信息。
        // UserAgent demo:
        // Mozilla/5.0 (Linux; Android 5.1; m3 note Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.2 TBS/036215 Safari/537.36 filter/com.filter/1.0.0/2/zh-CN (Android 5.1; Meizum3 note) meizu
        // Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13F69 filter/com.filter/1.0.0/10010/zh-CN (iOS 9.3.2)
        // Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13F69 filter/com.filter/1.0.0/1/zh-CN (Android 5.0.2; XiaomiRedmi Note 3) A360
        $pattern = '#([-_a-z0-9]+)/([.-_a-z0-9]+)/([_.a-z0-9]+)/([0-9]+|null)/([-a-zA-Z]+)\s+\((\S+)\s+([.0-9]+)(?:\s*;\s*(.+))?\)(?:\s+([-_a-zA-Z0-9]+))?$#i';
        if (preg_match($pattern, $user_agent, $matches)) {
            foreach ($matches as $index => $value) {
                switch ($index) {
                    case 1: // 应用名称。
                        $data['ua-app-name'] = $value;
                        break;
                    case 2: // 包名。
                        $data['ua-package-name'] = $value;
                        break;
                    case 3: // 应用版本号。
                        $data['ua-app-version'] = $value;
                        break;
                    case 4: // 应用ID。
                        if ($value != 'null') {
                            $data['ua-app-id'] = $value;
                        }
                        break;
                    case 5: // 语言。
                        if ($value != 'null') {
                            $data['ua-app-lang'] = $value;
                        }
                        break;
                    case 6: // 系统。
                        switch (strtolower($value)) {
                            case 'ios':
                                $data['ua-os'] = 'iOS';
                                break;
                            case 'android':
                                $data['ua-os'] = 'Android';
                                break;
                            default:
                                $data['ua-os'] = $value;
                        }
                        break;
                    case 7: // 系统版本。
                        $data['ua-os-version'] = $value;
                        break;
                    case 8: // 系统UI信息。
                        $data['ua-ui-info'] = $value;
                        break;
                    case 9: // 应用渠道。
                        $data['ua-channel'] = $value;
                        break;
                }
            }
        } else {

            // 兼容没有进行UserAgent统一的版本。


            // 系统。
            if (preg_match('/(iphone|ipad)/i', $user_agent)) {
                $data['ua-os'] = 'iOS';
            } elseif (preg_match('/(android)/i', $user_agent)) {
                $data['ua-os'] = 'Android';
            }
        }

        // 将结果并入Header。
        $request->headers->add($data);

        return $next($request);
    }
}
