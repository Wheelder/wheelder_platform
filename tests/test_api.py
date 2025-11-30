from fastapi.testclient import TestClient
import main

client = TestClient(main.app)

def test_root():
    resp = client.get('/')
    assert resp.status_code in (200, 204)
