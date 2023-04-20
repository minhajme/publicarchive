import com.jetbrains.handson.httpapi.module
import io.ktor.http.*
import io.ktor.server.testing.*
import org.junit.Test
import kotlin.test.assertEquals

class OrderTests {
    @Test
    fun testGetOrder() {
        withTestApplication({ module(testing = true) }) {
            handleRequest(HttpMethod.Get, "/order/2021-08-03-01").apply {
                assertEquals(
                    """{"number":"2021-08-03-01","contents":[{"item":"Beef Tehari","amount":5,"price":3.0},{"item":"Beef Kacci","amount":6,"price":3.0},{"item":"Matha(tokdoi, medium)","amount":11,"price":1.5}]}""",
                    response.content
                )
                assertEquals(HttpStatusCode.OK, response.status())
            }
        }
    }
}