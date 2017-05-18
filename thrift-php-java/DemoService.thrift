namespace php Woojean.Rpc.Demo  // PHP项目的命名空间
namespace java com.woojean.rpc.demo  // Java项目的命名空间

// 异常定义
exception RequestException {
}

// 参数定义
struct Param
{
    1:required string s1,
    2:required string s2,
}


// 服务定义
service DemoService
{
	// 定义一个连接字符串的方法，用一个指定的分隔符连接Param的所有属性，并返回一个完整的字符串
    string joinString(1:required Param p, 2:required string sep) 
        throws (1:RequestException e);
}ervicelient