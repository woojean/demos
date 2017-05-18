package com.woojean.rpc.demo;

import org.apache.thrift.TException;

public class RpcHandler implements DemoService.Iface{

    @Override
    public String joinString(Param p,String sep) throws TException {
        return p.getS1() + sep + p.getS2();
    }

}
