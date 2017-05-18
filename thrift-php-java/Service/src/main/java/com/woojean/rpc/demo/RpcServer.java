package com.woojean.rpc.demo;

import org.apache.thrift.TMultiplexedProcessor;
import org.apache.thrift.server.TThreadPoolServer;
import org.apache.thrift.transport.*;
import org.apache.thrift.server.TServer;

public class RpcServer {

    private void start() {
        try {
            TServerSocket serverTransport = new TServerSocket(9524);

            DemoService.Processor demoProcessor = new DemoService.Processor(new RpcHandler());

            TMultiplexedProcessor processor = new TMultiplexedProcessor();

            processor.registerProcessor("DemoService", demoProcessor);

            TServer server = new TThreadPoolServer(new TThreadPoolServer.Args(
                    serverTransport).processor(processor));
            System.out.println("Starting server on port 9524 ...");
            server.serve();

        } catch (TTransportException e) {
            e.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void main(String args[]) {
        RpcServer srv = new RpcServer();
        srv.start();
    }
}
