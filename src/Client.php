<?php

namespace Logan\Hunan;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use Logan\Hunan\exceptions\InitRuntimeException;

/**
 * 湖南省建筑工人实名制管理平台在线接口文档
 * http://113.247.238.148:9702/Interface_documentation.pdf
 */
class Client
{
    /**
     * 接口域名(带端口号)
     *
     * @var string
     */
    protected $domain;

    /**
     * 请求接口使用的 appId
     *
     * @var string
     */
    protected $appId;

    /**
     * 请求参数
     *
     * @var array
     */
    protected $params = [];

    /**
     * 请求接口 URI
     *
     * @var array
     */
    protected $uri = '';

    /**
     * GuzzleHttp 实例
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient = null;

    /**
     * 构造方法
     *
     * @param string $domain    接口地址
     * @param string $appId     appid
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function __construct(string $domain, string $appId)
    {
        $domain = rtrim($domain, '/');

        if (empty($appId)) {
            throw new InitRuntimeException("appId is not null", 0);
        }

        $this->domain     = $domain;
        $this->appId      = $appId;
        $this->httpClient = new HttpClient();
    }

    /**
     * 设置请求参数
     *
     * @param array $params 各接口请求的独自参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    private function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * 获取请求参数
     *
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 查询项目编码
     *
     * @param string $license       施工许可证
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryProjectCode(string $license)
    {
        $this->uri = $this->domain . '/api/receiver/open/project/queryProjectCode';
        $this->setParams([
            'builderLicenses' => $license
        ]);
        return $this;
    }

    /**
     * 分页查询项目信息
     *
     * @param string $proCode   项目编码
     * @param string $code      总承包单位统一社会信用代码
     * @param string $name      总承包单位名称
     * @param int $page         指定页号，以 1 为起始数字，表示第 1 页
     * @param int $pageSize     每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryProject(
        string $proCode = '',
        string $code = '',
        string $name = '',
        int $page = 1,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/project/queryProjectPageList';
        $this->setParams([
            'pageIndex'          => (string)$page,
            'pageSize'           => (string)$pageSize,
            'projectCode'        => $proCode,
            'contractorCorpCode' => $code,
            'contractorCorpName' => $name,
        ]);
        return $this;
    }

    /**
     * 上传项目信息
     *
     * @param array $proInfo    对应接口文档 POST 的 JSON 数据,二维数组
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-18
     */
    public function addProject(array $proInfo)
    {
        $this->uri = $this->domain . '/UploadSmz/UploadItemInfo';
        $this->setParams($proInfo);
        return $this;
    }

    /**
     * 分页查询单位信息
     *
     * @param string $proCode   项目编码
     * @param string $code      总承包单位统一社会信用代码
     * @param string $name      总承包单位名称
     * @param int $page         指定页号，以 1 为起始数字，表示第 1 页
     * @param int $pageSize     每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryProjectCompany(
        string $proCode,
        string $code = '',
        string $name = '',
        int $page = 1,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/corporation/queryCorporationPageList';
        $this->setParams([
            'pageIndex'          => (string)$page,
            'pageSize'           => (string)$pageSize,
            'projectCode'        => $proCode,
            'corpCode' => $code,
            'corpName' => $name,
        ]);
        return $this;
    }

    /**
     * 上传参建单位信息
     *
     * @param array $companyInfo    对应接口文档方法参数所有参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-18
     */
    public function addProjectCompany(array $companyInfo)
    {
        $this->uri = $this->domain . '/api/receiver/open/corporation/addCorporation';
        $this->setParams($companyInfo);
        return $this;
    }

    /**
     * 修改参建单位信息
     *
     * @param array $params     对应接口文档方法参数所有参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-18
     */
    public function updateProjectCompany(array $companyInfo)
    {
        $this->uri = $this->domain . '/api/receiver/open/corporation/updateCorporation';
        $this->setParams($companyInfo);
        return $this;
    }

    /**
     * 查询普通工人班组信息
     *
     * @param string $proCode       项目编码
     * @param string $groupCode     班组编号
     * @param string $groupName     班组名称
     * @param string $code          总承包单位统一社会信用代码
     * @param string $name          总承包单位名称
     * @param int $page             指定页号，以 1 为起始数字，表示第 1 页
     * @param int $pageSize         每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryGroup(
        string $proCode = '',
        string $groupCode = '',
        string $groupName = '',
        string $code = '',
        string $name = '',
        int $page = 1,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/group/queryGroupPageList';
        $this->setParams([
            'pageIndex'   => (string)$page,
            'pageSize'    => (string)$pageSize,
            'projectCode' => $proCode,
            'groupCode'   => $groupCode,
            'groupName'   => $groupName,
            'corpCode'    => $code,
            'corpName'    => $name,
        ]);
        return $this;
    }

    /**
     * 查询管理工人班组信息
     *
     * @param string $proCode       项目编码
     * @param string $code          单位统一社会信用代码
     * @param string $name          单位名称
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryManagerGroup(
        string $proCode,
        string $code = '',
        string $name = ''
    ) {
        $this->uri = $this->domain . '/api/receiver/open/group/queryManagerGroupList';
        $this->setParams([
            'projectCode' => $proCode,
            'corpCode'    => $code,
            'corpName'    => $name,
        ]);
        return $this;
    }

    /**
     * 上传班组信息
     *
     * @param array $groupInfo      对应接口文档方法参数所有参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function addGroup(array $groupInfo)
    {
        $this->uri = $this->domain . '/api/receiver/open/group/addGroup';
        $this->setParams($groupInfo);
        return $this;
    }

    /**
     * 修改班组信息
     *
     * @param string $code  班组编号
     * @param array $groupInfo   对应接口文档方法参数所有参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-08
     */
    public function updateGroup(string $code, array $groupInfo)
    {
        $this->uri = $this->domain . '/api/receiver/open/group/updateGroup';

        $groupInfo = $groupInfo + ['groupCode' => $code];
        $this->setParams($groupInfo);
        return $this;
    }

    /**
     * 查询工人合同信息
     *
     * @param string $proCode       项目编码
     * @param string $code          工人所属企业统一社会信用代码
     * @param string $name          工人所属企业名称
     * @param string $idCode        证件号码。AES
     * @param string $idCodeType    证件类型。参考人员证件类型字典表
     * @param string $periodType    合同期限类型: 0->固定期限合同,1->以完成一定工作为期限的合同
     * @param int $page             指定页号，以 1 为起始数字，表示第 1 页
     * @param int $pageSize         每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryContract(
        string $proCode,
        string $code = '',
        string $name = '',
        string $idCode = '',
        string $idCodeType = '',
        string $periodType = '',
        int $page = 1,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/contract/queryContractList';
        $this->setParams([
            'pageIndex'          => (string)$page,
            'pageSize'           => (string)$pageSize,
            'projectCode'        => $proCode,
            'corpCode'           => $code,
            'corpName'           => $name,
            'idCardType'         => $idCodeType,
            'IdCardNumber'       => $idCode,
            'contractPeriodType' => $periodType

        ]);
        return $this;
    }

    /**
     * 上传劳动合同
     *
     * @param string $proCode   项目编号
     * @param string $code      工人所属企业统一社会信用编码
     * @param string $name      工人所属企业名称
     * @param int $idCodeType   证件类型
     * @param string $idCode    证件号码
     * @param int $conType      合同期限类型
     * @param string $startDate 生效日期，yyyy-MM-dd
     * @param string $endDate   失效日期，yyyy-MM-dd
     * @param string $imgName   合同附件名称
     * @param string $imgBase64 附件Base64字符串，不超过 1M
     * @param string $number    合同编号
     * @param [type] $unit      计量单位
     * @param [type] $unitPrice 计量单价
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-14
     */
    public function addContract(
        string $proCode,
        string $code,
        string $name,
        int $idCodeType,
        string $idCode,
        int $conType,
        string $startDate,
        string $endDate,
        string $imgName,
        string $imgBase64,
        string $number = '',
        int $unit = NULL,
        float $unitPrice = NULL
    ) {
        $this->uri = $this->domain . '/api/receiver/open/contract/addContract';
        $this->setParams([
            'projectCode'  => $proCode,
            'contractList' => [
                [
                    'corpCode'           => $code,
                    'corpName'           => $name,
                    'idCardType'         => $idCodeType,
                    'idCardNumber'       => $idCode,
                    'contractPeriodType' => $conType,
                    'startDate'          => $startDate,
                    'endDate'            => $endDate,
                    'contractCode'       => $number,
                    'unit'               => $unit,
                    'unitPrice'          => $unitPrice,
                    'attachments'        => [
                        [
                            'name' => $imgName,
                            'data' => $imgBase64
                        ]
                    ],
                ]
            ]
        ]);
        return $this;
    }

    /**
     * 查询工人信息
     *
     * @param string $proCode       项目编号
     * @param int|null $groupCode   班组编号
     * @param string $code          工人所在企业统一社会信用代码
     * @param string $name          工人所在企业名称
     * @param string $idCode        证件号码。AES
     * @param string $idCodeType    证件类型。参考人员证件类型字典表
     * @param int $page             指定页号，以0 为起始数字，表示第1 页
     * @param int $pageSize         每页记录数，最多不能超过50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryWorkerInfo(
        string $proCode,
        int $groupCode = null,
        string $code = '',
        string $name = '',
        string $idCode = '',
        string $idCodeType = '',
        int $page = 0,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/projectWorker/queryProjectWorkerList';
        $this->setParams([
            'pageIndex'          => (string)$page,
            'pageSize'           => (string)$pageSize,
            'projectCode'        => $proCode,
            'corpCode'           => $code,
            'corpName'           => $name,
            'teamSysNo'          => $groupCode,
            'idCardType'         => $idCodeType,
            'IdCardNumber'       => $idCode,
        ]);
        return $this;
    }

    /**
     * 查询关键岗位人员信息及认证信息
     *
     * @param string $proCode       项目编号
     * @param string $idCode        工人身份证号
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-21
     */
    public function queryManagerWorkerInfo(string $proCode, string $idCode = '')
    {
        $this->uri = $this->domain . '/api/receiver/open/project/queryKeyPositionPersonnelCertification';
        $this->setParams([
            'idCardNumber' => $idCode,
            'projectCode'  => $proCode
        ]);
        return $this;
    }

    /**
     * 添加工人信息
     * 
     * 请注意以下字段的使用：
     * teamSysNo：在上传管理人员(workRole=10)时
     *      应使用新增加的查询管理人员班组信息接口（接口 4.4）
     *      查询 teamSysNo(班组编号)的值，其他相关接口（进退场、考勤）
     *      在上传管理人员数据时，班组也需要做对应改动。
     * workType(工种)将强制为：管理人员(900)。
     * manageType(管理岗位)不能为空，且 manageType 必须根据单位的参建类
     * 型取值，取值范围详见管理岗位字典表。
     * teamSysNo：在上传建筑工人(workRole=20)时，workType(工种)不能是管理
     * 人员(900)，也不能使用管理人员所在的班组。
     *
     * @param string $proCode   项目编号
     * @param string $code      企业信用代码
     * @param string $name      企业名称
     * @param string $groupCode 班组编号
     * @param string $groupName 班组名称
     * @param string $workersInfo 人员列表数据
     * 
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function addWorkerInfo(
        string $proCode,
        string $code,
        string $name,
        string $groupCode,
        string $groupName,
        array $workersInfo
    ) {
        $this->uri = $this->domain . '/api/receiver/open/projectWorker/addProjectWorker';
        $this->setParams([
            'projectCode' => $proCode,
            'corpCode'    => $code,
            'corpName'    => $name,
            'teamSysNo'   => $groupCode,
            'teamName'    => $groupName,
            'workerList'  => $workersInfo,
        ]);
        return $this;
    }

    /**
     * 编辑工人信息
     *
     * @param array $workerInfo    工人详细信息数组 对应接口文档所有参数
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-09
     */
    public function updateWorkerInfo(array $workerInfo)
    {
        $this->uri = $this->domain . '/api/receiver/open/projectWorker/updateProjectWorker';
        $this->setParams($workerInfo);
        return $this;
    }


    /**
     * 认证信息使用情况反馈
     *
     * @param string $idCode        工人身份证号
     * @param string $proCode       项目编码
     * @param string $confirmStatus 使用情况（0：未使用;1 已使用）
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-23
     */
    public function changeStatus(string $idCode, string $proCode, string $confirmStatus)
    {
        $this->uri = $this->domain . '/api/receiver/open/project/confirmAuthenticationInformation';
        $this->setParams([
            'idCardNumber'  => $idCode,
            'projectCode'   => $proCode,
            'confirmStatus' => $confirmStatus
        ]);
        return $this;
    }

    /**
     * 分页查询工人进出场信息
     *
     * @param string $proCode           项目编码
     * @param string $code              工人所在企业统一社会信用代码
     * @param string $name              工人所在企业名称
     * @param int|null $groupCode       班组编号
     * @param string|null $idCodeType   证件类型。参考人员证件类型字典表
     * @param string|null $idCode       证件号码。AES
     * @param int $page                 指定页号，以 0 为起始数字，表示第 1 页
     * @param int $pageSize             每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-23
     */
    public function queryProjectWorker(
        string $proCode,
        ?string $code = '',
        ?string $name = '',
        ?int $groupCode = null,
        ?string $idCodeType = '',
        ?string $idCode = '',
        int $page = 0,
        int $pageSize = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/workerEntryExit/queryWorkerEntryExit';
        $this->setParams([
            'pageIndex'    => (string)$page,
            'pageSize'     => (string)$pageSize,
            'projectCode'  => $proCode,
            'corpCode'     => $code,
            'corpName'     => $name,
            'teamSysNo'    => $groupCode,
            'idCardType'   => $idCodeType,
            'IdCardNumber' => $idCode,
        ]);
        return $this;
    }

    /**
     * 添加项目工人
     * 工人进场
     *
     * @param int $groupCode    班组编号
     * @param string $proCode   项目编号
     * @param string $code      工人所属企业统一社会信用代码
     * @param string $name      工人所属企业名称
     * @param int $idCodeType   证件类型。参考人员证件类型字典表
     * @param string $idCode    证件号码。AES
     * @param string $date      进场日期 yyyy-MM-dd
     * @param string $img       凭证扫描件。不超过 50KB 的 Base64 字符串或文件地址，支持格式(jpg， jpeg， png， pdf)
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function addProjectWorker(
        int $groupCode,
        string $proCode,
        string $code,
        string $name,
        string $idCodeType,
        string $idCode,
        string $date,
        string $img = ''
    ) {
        $this->uri = $this->domain . '/api/receiver/open/workerEntryExit/addWorkerEntryExit';
        $this->setParams([
            'projectCode' => $proCode,
            'corpCode'    => $code,
            'corpName'    => $name,
            'teamSysNo'   => $groupCode,
            'workerList'  => [
                [
                    'idCardType'   => $idCodeType,
                    'idCardNumber' => $idCode,
                    'date'         => $date,
                    'type'         => 1,    // 1->in;0->out
                    'voucher'      => $img ?: NULL,
                ]
            ],
        ]);
        return $this;
    }

    /**
     * 删除项目工人
     * 工人退场
     *
     * @param int $groupCode    班组编号
     * @param string $proCode   项目编号
     * @param string $code      工人所属企业统一社会信用代码
     * @param string $name      工人所属企业名称
     * @param int $idCodeType   证件类型。参考人员证件类型字典表
     * @param string $idCode    证件号码。AES
     * @param string $date      进场日期 yyyy-MM-dd
     * @param string $img       凭证扫描件。不超过 50KB 的 Base64 字符串或文件地址，支持格式(jpg， jpeg， png， pdf)
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function exitProjectWorker(
        int $groupCode,
        string $proCode,
        string $code,
        string $name,
        string $idCodeType,
        string $idCode,
        string $date,
        string $img = ''
    ) {
        $this->uri = $this->domain . '/api/receiver/open/workerEntryExit/addWorkerEntryExit';
        $this->setParams([
            'projectCode' => $proCode,
            'corpCode'    => $code,
            'corpName'    => $name,
            'teamSysNo'   => $groupCode,
            'workerList'  => [
                [
                    'idCardType'   => $idCodeType,
                    'idCardNumber' => $idCode,
                    'date'         => $date,
                    'type'         => 0,    // 1->in;0->out
                    'voucher'      => $img ?: NULL,
                ]
            ],
        ]);
        return $this;
    }

    /**
     * 分页查询人员考勤信息
     *
     * @param string $proCode      项目编码
     * @param string $date         考勤日期。格式 yyyy-MM-dd
     * @param int|null $groupCode  班组编号
     * @param string $idCode       证件号码。AES
     * @param string $idCodeType   证件类型。参考人员证件类型字典表
     * @param int $page            指定页号，以 0 为起始数字，表示第 1 页
     * @param int $pageSize        每页记录数，最多不能超过 50
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-23
     */
    public function queryAttendance(
        string $proCode,
        string $date,
        ?int   $groupCode  = null,
        string $idCode     = '',
        string $idCodeType = '',
        int    $page       = 0,
        int    $pageSize   = 50
    ) {
        $this->uri = $this->domain . '/api/receiver/open/attendance/queryWorkerAttendanceList';
        $this->setParams([
            'pageIndex'    => (string)$page,
            'pageSize'     => (string)$pageSize,
            'projectCode'  => $proCode,
            'swipeTime'    => $date,
            'teamSysNo'    => $groupCode,
            'idCardType'   => $idCodeType,
            'IdCardNumber' => $idCode,
        ]);
        return $this;
    }

    /**
     * 考勤上报
     *
     * @param string $proCode       项目编码
     * @param int $groupCode        班组编号
     * @param string $idCode        证件号码。AES
     * @param string $dateTime      刷卡时间，yyyy-MM-dd HH:mm:ss
     * @param string $devCode       考勤设备编号
     * @param bool $isIn            是否为进场
     * @param string $image         刷卡近照。Base64 字符串或图片地址，支持格式(jpg, png, jpeg)，不超过 50 KB
     * @param string|null $channel  通道的名称
     * @param string|null $type     通行方式。参考工人通行方式字典表
     * @param float|null $lng       WGS84 经度
     * @param float|null $lat       WGS84 纬
     * @param string $idCodeType    证件类型。参考人员证件类型字典表
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-23
     */
    public function addAttendance(
        string $proCode,
        int $groupCode,
        string $idCode,
        string $dateTime,
        string $devCode,
        bool $isIn,
        string $image,
        ?string $channel    = null,
        ?string $type       = null,
        ?float  $lng        = null,
        ?float  $lat        = null,
        string  $idCodeType = '01'
    ) {
        $this->uri = $this->domain . '/api/receiver/open/attendance/addWorkerAttendance';
        $this->setParams([
            'projectCode' => $proCode,
            'teamSysNo'   => $groupCode,
            'dataList'    => [
                [
                    'idCardType'   => $idCodeType,
                    'idCardNumber' => $idCode,
                    'swipeTime'    => $dateTime,
                    'equipmentNum' => $devCode,
                    'direction'    => $isIn ? '01' : '02',   // 01->in ; 02->out
                    'image'        => $image,
                    'channel'      => $channel,
                    'attendType'   => $type,
                    'lng'          => $lng,
                    'lat'          => $lat,
                ]
            ]
        ]);
        return $this;
    }


    /**
     * 关键岗位人员考勤上报
     *
     * @param string $proCode       项目编码
     * @param int $groupCode        班组编号
     * @param string $idCode        证件号码。AES
     * @param string $dateTime      刷卡时间，yyyy-MM-dd HH:mm:ss
     * @param string $devCode       考勤设备编号
     * @param bool $isIn            是否为进场
     * @param string $image         刷卡近照。Base64 字符串或图片地址，支持格式(jpg, png, jpeg)，不超过 50 KB
     * @param string|null $channel  通道的名称
     * @param string|null $type     通行方式。参考工人通行方式字典表
     * @param float|null $lng       WGS84 经度
     * @param float|null $lat       WGS84 纬
     * @param string $idCodeType    证件类型。参考人员证件类型字典表
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-23
     */
    public function addManagerAttendance(
        string $proCode,
        int $groupCode,
        string $idCode,
        string $dateTime,
        string $devCode,
        bool $isIn,
        string $image,
        ?string $channel    = null,
        ?string $type       = null,
        ?float  $lng        = null,
        ?float  $lat        = null,
        string  $idCodeType = '01'
    ) {
        $this->uri = $this->domain . '/api/receiver/open/attendance/addkeyPositionsAttendance';
        $this->setParams([
            'projectCode' => $proCode,
            'teamSysNo'   => $groupCode,
            'dataList'    => [
                [
                    'idCardType'   => $idCodeType,
                    'idCardNumber' => $idCode,
                    'swipeTime'    => $dateTime,
                    'equipmentNum' => $devCode,
                    'direction'    => $isIn ? '01' : '02',   // 01->in ; 02->out
                    'image'        => $image,
                    'channel'      => $channel,
                    'attendType'   => $type,
                    'lng'          => $lng,
                    'lat'          => $lat,
                ]
            ]
        ]);
        return $this;
    }

    /**
     * 发起接口请求
     *
     * @param string $method
     * @param bool $isDebug
     * @return void
     * @author LONG <1121116451@qq.com>
     * @version version
     * @date 2022-02-11
     */
    public function send(string $method = 'POST')
    {
        $response = $this->httpClient->request($method, $this->uri, [
            RequestOptions::HEADERS => [
                'applyId' => $this->appId
            ],
            RequestOptions::JSON => $this->params
        ])
            ->getBody()
            ->getContents();

        $res = json_decode($response, true);

        if ($res && $res['data']) {
            // data 结果统一 JSON decode 返回
            $res['data'] = json_decode($res['data'], true);
        }

        return $res;
    }
}
